#!/usr/bin/env bash
# setup-local.sh — PHP built-in server + SQLite (docroot: public/)
set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

PUBLIC_DIR="${PUBLIC_DIR:-public}"
# Sur /mnt/team (NFS), SQLite se verrouille → DB locale par défaut
DATA_DIR="${DATA_DIR:-$HOME/.cache/capsule}"
DB_SQLITE="${DB_SQLITE:-$DATA_DIR/database.sqlite}"
MIG_FILE="${MIG_FILE:-$ROOT/database/migrations/sqlite_init.sql}"
SEED_FILE="${SEED_FILE:-$ROOT/database/seeds/local.sql}"
PORT="${PORT:-8080}"
INDEX_DIR="${INDEX_DIR:-$ROOT/storage/index}"

log() { printf '· %s\n' "$*"; }
ok() { printf '✔ %s\n' "$*"; }
warn() { printf '⚠ %s\n' "$*"; }
err() { printf '✖ %s\n' "$*" >&2; }
die() { err "$*"; exit 1; }
need() { command -v "$1" >/dev/null 2>&1 || die "Manque binaire: $1"; }

ensure_env() {
  if [[ ! -f "$ROOT/.env" ]]; then
    cp "$ROOT/.env.example" "$ROOT/.env"
    ok "Créé .env depuis .env.example"
  fi
}

ensure_dirs() {
  if ! mkdir -p "$DATA_DIR" 2>/dev/null; then
    DATA_DIR="/tmp/capsule-local"
    DB_SQLITE="$DATA_DIR/database.sqlite"
    warn "Impossible d'écrire dans le cache user → fallback $DATA_DIR"
  fi
  mkdir -p "$DATA_DIR" "$ROOT/storage"/{logs,cache,index} \
    "$ROOT/public/uploads"/{img/temp,img/thumbnails,pdf} "$ROOT/bin"
}

write_env_db_path() {
  ensure_env
  # Force le chemin SQLite local dans .env
  if grep -q '^DB_DATABASE=' "$ROOT/.env"; then
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_SQLITE}|" "$ROOT/.env"
  else
    echo "DB_DATABASE=${DB_SQLITE}" >>"$ROOT/.env"
  fi
  if grep -q '^DB_DRIVER=' "$ROOT/.env"; then
    sed -i 's|^DB_DRIVER=.*|DB_DRIVER=sqlite|' "$ROOT/.env"
  else
    echo "DB_DRIVER=sqlite" >>"$ROOT/.env"
  fi
}

detect_pkg() {
  if command -v dnf >/dev/null 2>&1; then echo dnf
  elif command -v apt-get >/dev/null 2>&1; then echo apt
  else echo unknown; fi
}

install_deps() {
  local pm
  pm="$(detect_pkg)"
  case "$pm" in
    apt)
      sudo apt-get update
      sudo apt-get install -y php-cli sqlite3 php-sqlite3 php-mysql php-intl
      ;;
    dnf)
      sudo dnf install -y php-cli php-pdo php-sqlite3 php-mysqlnd php-intl sqlite
      ;;
    *) die "Installe manuellement php-cli php-sqlite3 sqlite3" ;;
  esac
  ok "Dépendances installées"
  php -m | grep -Ei 'pdo|sqlite|mysql|intl' || true
}

sqlite_init() {
  need sqlite3
  [[ -f "$MIG_FILE" ]] || die "Migration absente: $MIG_FILE"
  # Sur FS réseau (NFS), plusieurs connexions sqlite3 plantent en "database is locked".
  rm -f "$DB_SQLITE" "$DB_SQLITE"-journal "$DB_SQLITE"-wal "$DB_SQLITE"-shm
  {
    echo "PRAGMA busy_timeout=5000;"
    cat "$MIG_FILE"
    [[ -f "$SEED_FILE" ]] && cat "$SEED_FILE"
  } | sqlite3 "$DB_SQLITE"
  ok "SQLite prête: $DB_SQLITE"
}

sqlite_init_if_absent() {
  if [[ -f "$DB_SQLITE" ]]; then
    log "DB déjà présente: $DB_SQLITE (skip). make reset pour repartir de zéro."
  else
    sqlite_init
  fi
}

cmd="${1:-help}"
case "$cmd" in
  info)
    php -v
    php -m | grep -Ei 'pdo|sqlite|mysql|intl' || true
    command -v sqlite3 && sqlite3 --version || true
    echo "Public : $PUBLIC_DIR"
    echo "SQLite : $DB_SQLITE"
    echo "Mig    : $MIG_FILE"
    ;;
  deps) install_deps ;;
  init)
    need php
    ensure_env
    ensure_dirs
    write_env_db_path
    sqlite_init_if_absent
    ok "Init OK → make dev"
    ;;
  reset)
    ensure_dirs
    write_env_db_path
    sqlite_init
    ;;
  seed)
    need sqlite3
    [[ -f "$DB_SQLITE" ]] || die "DB absente, lance make init"
    sqlite3 "$DB_SQLITE" <"$SEED_FILE"
    ok "Seed appliqué"
    ;;
  dev)
    need php
    [[ -d "$PUBLIC_DIR" ]] || die "Dossier $PUBLIC_DIR manquant"
    ensure_env
    echo "→ http://localhost:${PORT}  (CTRL+C pour arrêter)"
    php -d display_errors=1 -d error_reporting=32767 -S "localhost:${PORT}" -t "$PUBLIC_DIR"
    ;;
  db.shell)
    need sqlite3
    sqlite3 "$DB_SQLITE"
    ;;
  *)
    cat <<USAGE
Usage: $0 {info|deps|init|reset|seed|dev|db.shell}
USAGE
    exit 1
    ;;
esac
