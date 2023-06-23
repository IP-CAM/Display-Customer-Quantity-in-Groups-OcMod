<!--
<insertfile>_inc/summary.txt</insertfile>
-->

<?php $module = 'module_customer_group_size'; ?>

<?php $links = array(
	'<i class="fa fa-home" aria-hidden="true"></i>'     => '<insertvar>MODLINK</insertvar>',
	'<i class="fa fa-opencart" aria-hidden="true"></i>' => '<insertvar>OCMLINK</insertvar>',
	'<i class="fa fa-github" aria-hidden="true"></i>'   => '<insertvar>GITLINK</insertvar>',
	'MIT License'                                       => '<insertvar>LICLINK</insertvar>',
	'<insertvar>EMAIL</insertvar>'                      => 'mailto:<insertvar>EMAIL</insertvar>?Subject=[OpenCart] <insertvar>MODNAME</insertvar>',
); ?>

<?php $cyear = date('Y') != '<insertvar>MODYEAR</insertvar>' ? '-' . date('Y') : ''; ?>
<?php $copyright = '© <insertvar>AUTHOR</insertvar>, <insertvar>MODYEAR</insertvar>' . $cyear; ?>
<?php $version = 'Version <insertvar>MODVERS</insertvar>'; ?>

<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button
          class="btn btn-primary"
          data-toggle="tooltip"
          form="form-module"
          title="<?php echo $button_save; ?>"
          type="submit"><i class="fa fa-save"></i>
        </button>
        <a
          class="btn btn-success"
          data-toggle="tooltip"
          onclick="$('#form-module').attr('action', '<?php echo $submit; ?>'); $('#form-module').submit();"
          title="<?php echo $button_submit; ?>"><i class="fa fa-check"></i>
        </a>
        <a
          class="btn btn-default"
          data-toggle="tooltip"
          href="<?php echo $cancel; ?>"
          title="<?php echo $button_back; ?>"><i class="fa fa-reply"></i>
        </a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <br />
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $_key => $breadcrumb) { ?>
        <li>
          <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        </li><?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_permission) { ?>
    <div class="alert alert-danger alert-dismissible">
      <i class="fa fa-exclamation-circle"></i>
      <?php echo $error_permission; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success alert-dismissible">
      <i class="fa fa-exclamation-circle"></i>
      <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form
          action="<?php echo $action; ?>"
          class="form-horizontal"
          enctype="multipart/form-data"
          id="form-module"
          method="post">
          <div class="form-group">
            <div class="btn-group btn-group-toggle col-sm-2" data-toggle="buttons" id="input-status">
                <label class="btn btn-default btn-enabled<?php if ($status) { ?> active btn-success<?php } ?>">
                  <input
                    autocomplete="off"
                    name="<?php echo $module; ?>_status"
                    type="radio"
                    value="1" <?php if ($status) { ?>checked<?php } ?>/><?php echo $text_enabled; ?>
                </label>
                <label class="btn btn-default btn-disabled<?php if (!$status) { ?> active btn-danger<?php } ?>">
                  <input
                    autocomplete="off"
                    name="<?php echo $module; ?>_status"
                    type="radio"
                    value="0" <?php if (!$status) { ?>checked<?php } ?>><?php echo $text_disabled; ?>
                </label>
            </div>
          </div>

          <div class="tab-content">
            <div class="col-sm-12 text-center"><?php echo $text_about; ?></div>
            <div class="text-center">
              <?php $i = 1; ?>
              <?php foreach ($links as $name => $url): ?>
                <a href="<?php echo $url; ?>" target="_blank"><?php echo $name; ?></a>
                <?php if ($i < count($links)) { ?> | <?php } ?>
                <?php $i++; ?>
              <?php endforeach; ?>
            </div>
            <hr/>
            <div class="text-center"><?php echo $copyright; ?></div>
            <div class="text-center"><?php echo $version; ?></div>
            <div class="text-center"><?php echo $text_made; ?></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  <!--
  $('input[type="text"]').on('input', function() {
    $(this).attr('value', $(this).val());
  });

  $('#input-status .btn.btn-default').click(function() {
    if (!$(this).hasClass('active')) {
      if ($(this).hasClass('btn-enabled')) {
        $('#input-status .btn.btn-default').removeClass('btn-danger');
        $(this).addClass('btn-success');
      }

      if ($(this).hasClass('btn-disabled')) {
        $('#input-status .btn.btn-default').removeClass('btn-success');
        $(this).addClass('btn-danger');
      }
    }
  });

  -->
</script>
<?php echo $footer;
?>
