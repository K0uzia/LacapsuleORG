<?php
/** Modal générique ressources + services */
?>
<div class="modal" id="site-modal" hidden aria-hidden="true">
    <div class="modal__backdrop" data-modal-close tabindex="-1"></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <button type="button" class="modal__close" data-modal-close aria-label="Fermer">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
        <div class="modal__media" data-modal-media hidden></div>
        <div class="modal__body">
            <p class="modal__eyebrow" data-modal-eyebrow></p>
            <h2 class="modal__title" id="modal-title" data-modal-title></h2>
            <p class="modal__subtitle" data-modal-subtitle></p>
            <div class="modal__content" data-modal-content></div>
            <div class="modal__actions" data-modal-actions></div>
        </div>
    </div>
</div>
