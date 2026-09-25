<?php
function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}





function resize_img($image_path, $image_dest, $max_size = 400, $qualite = 100, $type = 'auto', $upload = false)
{

    // Vérification que le fichier existe
    if (!file_exists($image_path)) {
        return 'wrong_path';
    }

    if ($image_dest == "") {
        $image_dest = $image_path;
    }
    // Extensions et mimes autorisés
    $extensions = array('jpg', 'jpeg', 'png', 'gif');
    $mimes = array('image/jpeg', 'image/gif', 'image/png');

    // Récupération de l'extension de l'image
    $tab_ext = explode('.', $image_path);
    $extension = strtolower($tab_ext[count($tab_ext) - 1]);

    // Récupération des informations de l'image
    $image_data = getimagesize($image_path);

    // Si c'est une image envoyé alors son extension est .tmp et on doit d'abord la copier avant de la redimentionner
    if ($upload && in_array($image_data['mime'], $mimes)) {
        copy($image_path, $image_dest);
        $image_path = $image_dest;

        $tab_ext = explode('.', $image_path);
        $extension = strtolower($tab_ext[count($tab_ext) - 1]);
    }

    // Test si l'extension est autorisée
    if (in_array($extension, $extensions) && in_array($image_data['mime'], $mimes)) {

        // On stocke les dimensions dans des variables
        $img_width = $image_data[0];
        $img_height = $image_data[1];

        // On vérifie quel coté est le plus grand
        if ($img_width >= $img_height && $type != "height") {

            // Calcul des nouvelles dimensions à partir de la largeur
            if ($max_size >= $img_width) {
                return 'no_need_to_resize';
            }

            $new_width = $max_size;
            $reduction = (($new_width * 100) / $img_width);
            $new_height = round((($img_height * $reduction) / 100), 0);
        } else {

            // Calcul des nouvelles dimensions à partir de la hauteur
            if ($max_size >= $img_height) {
                return 'no_need_to_resize';
            }

            $new_height = $max_size;
            $reduction = (($new_height * 100) / $img_height);
            $new_width = round((($img_width * $reduction) / 100), 0);
        }

        // Création de la ressource pour la nouvelle image
        $dest = imagecreatetruecolor($img_width, $img_height);
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
        imagefilledrectangle($dest, 0, 0, $img_width, $img_height, $transparent);

        // En fonction de l'extension on prépare l'iamge
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($image_path); // Pour les jpg et jpeg
                break;

            case 'png':
                $src = imagecreatefrompng($image_path); // Pour les png
                break;

            case 'gif':
                $src = imagecreatefromgif($image_path); // Pour les gif
                break;
        }

        // Création de l'image redimentionnée
        if (imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)) {

            // On remplace l'image en fonction de l'extension
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($dest, $image_dest, $qualite); // Pour les jpg et jpeg
                    break;

                case 'png':
                    $compression = round((100 - $qualite) / 10, 0);
                    imagepng($dest, $image_dest, $compression); // Pour les png
                    break;

                case 'gif':
                    imagegif($dest, $image_dest); // Pour les gif
                    break;
            }

            return 'success';
        } else {
            return 'resize_error';
        }
    } else {
        return 'no_img';
    }
}

/**
 * Fonction de création de slug
 *
 * @param string $string Chaine de caratère à slugger
 * @return string
 */
function generateSlug(string $string)
{
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}
/**
 * Fonction de debug
 *
 * @param [type] ...$vars
 * @return void
 */
function dd(...$vars)
{
    echo
    '<style>
            .sf-dump {
                font-family: Menlo, Monaco, Consolas, monospace;
                font-size: 12px;
                background-color: #F7F7F7;
                color: #333;
                padding: 5px;
                border: 1px solid #CCC;
                border-radius: 4px;
                margin-bottom: 10px;
                overflow: auto;
            }
            
            .sf-dump .sf-dump-compact {
                display: none;
            }
            
            .sf-dump .sf-dump-toggle {
                display: none;
            }
            
            .sf-dump .sf-dump-ellipsis {
                color: #AAA;
            }
            
            .sf-dump .sf-dump-str {
                color: #2196F3;
            }
            
            .sf-dump .sf-dump-num {
                color: #F44336;
            }
            
            .sf-dump .sf-dump-const {
                color: #1E88E5;
            }
            
            .sf-dump .sf-dump-key {
                color: #795548;
            }
            
            .sf-dump .sf-dump-public {
                color: #4CAF50;
            }
            
            .sf-dump .sf-dump-protected {
                color: #FFC107;
            }
            
            .sf-dump .sf-dump-private {
                color: #F44336;
            }
            </style>';
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    die();
}
