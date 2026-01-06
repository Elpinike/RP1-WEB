console.log("✅ td-filters.js loaded!");

$(document).ready(function() {

  // Start with model dropdown disabled
  $('#model').prop('disabled', true);

  // When condition (new / preowned) changes → load makes
  $('input[name="condition"]').change(function() {
    console.log("Condition clicked:", $(this).val());
    const condition = $(this).val();
    $('#make').html('<option value="">Chargement...</option>');
    $('#model').html('<option value="">Choisir un modèle</option>').prop('disabled', true);

    $.get('controllers/get_makes.php', { condition: condition }, function(data) {
      $('#make').html(data);
    });
  });

  // When make changes → load models
  $('#make').change(function() {
    const condition = $('input[name="condition"]:checked').val();
    const make_id = $(this).val();

    if (!make_id) {
      $('#model')
        .html('<option value="">-- Choisir un modèle --</option>')
        .prop('disabled', true);
      return;
    }

    $('#model').html('<option value="">Chargement...</option>').prop('disabled', false);

    $.get('controllers/get_models.php', { condition: condition, make_id: make_id }, function(data) {
      $('#model').html(data);
    });
  });

});
