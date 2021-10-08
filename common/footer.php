</div>
<!-- /container -->



<form>
<div class="modal" tabindex="10" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo _('Confirmar eliminar registro?');?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-xs" id="modal-btn-si"><?php echo _('Si');?></button>
        <button type="button" class="btn btn-primary btn-xs" id="modal-btn-no"><?php echo _('No');?></button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="10" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="print-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="PrintModalLabel"><?php echo _('Confirmar reimprimir documento?');?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-xs" id="modal-btn-psi"><?php echo _('Si');?></button>
        <button type="button" class="btn btn-primary btn-xs" id="modal-btn-pno"><?php echo _('No');?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="10" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mail-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mailModalLabel"><?php echo _('Confirmar reenviar documento?');?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-xs" id="modal-btn-msi"><?php echo _('Si');?></button>
        <button type="button" class="btn btn-primary btn-xs" id="modal-btn-mno"><?php echo _('No');?></button>
      </div>
    </div>
  </div>
</div>

<div id="loginModal" class="modal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title form-signin-heading"><?php echo _('Ingrese contaseña');?></h4>
            </div>
            <div class="modal-body">

                    <div id="form-group-password">
                        <label for="inputPassword" class="sr-only"><?php echo _('Contraseña');?></label>
                        <input type="password" id="inputPassword" class="form-control input-sm" placeholder="Contraseña" autocomplete="off" required>
                        <input type="hidden" id="Sendcod">
                        <input type="hidden" id="Sendtabla">
                        <span id="form-span-password" aria-hidden="true"></span>
                    </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-xs" id="PassSubmitDel" style="display:none;"><?php echo _('Confirmar');?></button>
              <button type="button" class="btn btn-default btn-xs" id="PassSubmitPrint" style="display:none;"><?php echo _('Confirmar');?></button>
                <button type="button" class="btn btn-default btn-xs" id="PassSubmitMail" style="display:none;"><?php echo _('Confirmar');?></button>
                <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal"><?php echo _('Cerrar');?></button>

                </button>
            </div>
        </div>
    </div>
</div>

</form>

</body>
</html>