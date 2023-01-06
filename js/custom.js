var req_fields = [];
$(document).ready(function () {
    var ajax_get_benefits_balances = $('#payee').val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "inc/config.php",
        data: {
            ajax_get_benefits_balances: ajax_get_benefits_balances
        },
        success: function (response) {
            var data = JSON.parse(response);
            $('#car_maintenance_balance').val(data[0].car_maintenance);
            $('#cep_balance').val(data[1].cep);
            $('#reimbusesable_liters').val(data[2].gas);
            $('#gym_balance').val(data[3].gym);
            $('#medical_balance').val(data[4].medical);
            $('#optical_balance').val(data[5].optical);
        }
    });
});
$(".add_parking").click(function () {
    var lastField = $("#div_add_parking div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var parking_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" name="parking_requested_amount[]" step=".01" class="form-control parking_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="parking_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="parking_attachment[]" class="form-control parking_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(parking_details);
    fieldWrapper.append(removeButton);
    $("#div_add_parking").append(fieldWrapper);
});
$(".add_gas").click(function () {
    var lastField = $("#div_add_gas div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var gas_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="gas_requested_amount[]" class="form-control gas_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="gas_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="gas_attachment[]" class="form-control gas_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(gas_details);
    fieldWrapper.append(removeButton);
    $("#div_add_gas").append(fieldWrapper);
});
$(".add_car").click(function () {
    var lastField = $("#div_add_car div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var car_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="car_requested_amount[]" class="form-control car_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="car_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="car_attachment[]" class="form-control car_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(car_details);
    fieldWrapper.append(removeButton);
    $("#div_add_car").append(fieldWrapper);
});
$(".add_medical").click(function () {
    var lastField = $("#div_add_medical div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var medical_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="medical_requested_amount[]" class="form-control medical_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="medical_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="medical_attachment[]" class="form-control medical_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(medical_details);
    fieldWrapper.append(removeButton);
    $("#div_add_medical").append(fieldWrapper);
});
$(".add_gym").click(function () {
    var lastField = $("#div_add_gym div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var gym_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="gym_requested_amount[]" class="form-control gym_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="gym_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="gym_attachment[]" class="form-control gym_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(gym_details);
    fieldWrapper.append(removeButton);
    $("#div_add_gym").append(fieldWrapper);
});
$(".add_optical").click(function () {
    var lastField = $("#div_add_optical div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var optical_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="optical_requested_amount[]" class="form-control optical_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="optical_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="optical_attachment[]" class="form-control optical_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(optical_details);
    fieldWrapper.append(removeButton);
    $("#div_add_optical").append(fieldWrapper);
});
$(".add_cep").click(function () {
    var lastField = $("#div_add_cep div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var cep_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="cep_requested_amount[]" class="form-control cep_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="cep_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="cep_attachment[]" class="form-control cep_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(cep_details);
    fieldWrapper.append(removeButton);
    $("#div_add_cep").append(fieldWrapper);
});
$(".add_club").click(function () {
    var lastField = $("#div_add_club div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var club_details = $(
        '<div class="col-md-3"> <label>Requested Amount</label> <input type="number" name="club_requested_amount[]" step=".01" class="form-control club_requested_amount"></div><div class="col-md-4"> <label>Remarks</label> <input type="text" name="club_remarks[]" class="form-control"></div><div class="col-md-4"> <label>Attachment</label> <input type="file" name="club_attachment[]" class="form-control club_attachment"></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(club_details);
    fieldWrapper.append(removeButton);
    $("#div_add_club").append(fieldWrapper);
});
$(".add_maternity").click(function () {
    var lastField = $("#div_add_maternity div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var maternity_details = $(
        '<div class="col-md-3"><div class="form-group"> <label>Requested Amount</label> <input type="number" step=".01" name="maternity_requested_amount[]" class="form-control maternity_requested_amount"></div></div><div class="col-md-4"><div class="form-group"> <label>Remarks</label> <input type="text" name="maternity_remarks[]" class="form-control"></div></div><div class="col-md-4"><div class="form-group"> <label>Attachment</label> <input type="file" name="maternity_attachment[]" class="form-control maternity_attachment"></div></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(maternity_details);
    fieldWrapper.append(removeButton);
    $("#div_add_maternity").append(fieldWrapper);
});
$(".add_others").click(function () {
    var lastField = $("#div_add_others div:last");
    var intId =
        (lastField && lastField.length && lastField.data("idx") + 1) || 1;
    var fieldWrapper = $('<div class="row" id="field' + intId + '"/>');
    fieldWrapper.data("idx", intId);

    var others_details = $(
        '<div class="col-md-3"> <label>Requested Amount</label> <input type="number" name="others_requested_amount[]" step=".01" class="form-control others_requested_amount"></div><div class="col-md-4"> <label>Remarks</label> <input type="text" name="others_remarks[]" class="form-control"></div><div class="col-md-4"> <label>Attachment</label> <input type="file" name="others_attachment[]" class="form-control others_attachment"></div>'
    );
    var removeButton = $(
        "<div class='col-md-1 rem_button'><button class='btn btn-danger btn-sm' title='Remove'>–</button></div><br>"
    );
    removeButton.click(function () {
        $(this).parent().remove();
    });
    fieldWrapper.append(others_details);
    fieldWrapper.append(removeButton);
    $("#div_add_others").append(fieldWrapper);
});
$('#payee').change(function () {
    var get_benefits_category = $(this).val();
    var ajax_get_benefits_balances = $(this).val();
    $.ajax({
        type: "POST",
        url: "inc/config.php",
        data: {
            get_benefits_category: get_benefits_category
        },
        success: function (response) {
            $("#benefits_categories").html(response);
        }
    });
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "inc/config.php",
        data: {
            ajax_get_benefits_balances: ajax_get_benefits_balances
        },
        success: function (response) {
            var data = JSON.parse(response);
            $('#car_maintenance_balance').val(data[0].car_maintenance);
            $('#cep_balance').val(data[1].cep);
            $('#reimbusesable_liters').val(data[2].gas);
            $('#gym_balance').val(data[3].gym);
            $('#medical_balance').val(data[4].medical);
            $('#optical_balance').val(data[5].optical);
        }
    });
});
$('#benefits_categories').on('click', 'a', function (e) {
    e.preventDefault()
    $(this).tab('show');
});
$('#maternity_type').change(function () {
    var val = $(this).val();
    if (val === "Normal") {
        $('#maternity_max').val(150000);
        $('.maternity_requested_amount').attr('max', '150000');
        $('.maternity_requested_amount').attr('data-max', '150000');
    }
    if (val === "CS") {
        $('#maternity_max').val(200000);
        $('.maternity_requested_amount').attr('max', '200000');
        $('.maternity_requested_amount').attr('data-max', '200000');
    }
    if (val === "Misc") {
        $('#maternity_max').val(100000);
        $('.maternity_requested_amount').attr('max', '100000');
        $('.maternity_requested_amount').attr('data-max', '100000');
    }
});
$('#requested_liters').keyup(function () {
    $('.gas_requested_amount').prop('required', true);
    requested_liters = parseInt($('#requested_liters').val());
    reim_liters = parseInt($('#reimbusesable_liters').val());
    if (requested_liters > reim_liters) {
        $('#w_gas').text('You have exceeded the maximum amount limit');
        $('#btn_benefits_reimbursement').prop('disabled', true);
        if (req_fields.indexOf('Gas') === -1) {
            req_fields.push('Gas')
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Gas';
            });
        }
    } else {
        $('#w_gas').text('');
        $('#btn_benefits_reimbursement').prop('disabled', false);
    }
});
$(document).on('keyup change', '.gas_requested_amount', function () {
    var total = 0;
    var gas_requested_amount = parseFloat($('.gas_requested_amount').val());
    $('.gas_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val());
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = gas_requested_amount ? total + val : '';
        $("#gas_total").val(Number(total).toLocaleString('en'));
    });
    if (total) {
        if ($(".gas_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Gas') === -1) {
                req_fields.push('Gas')
            }
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Gas';
            });
        }
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.parking_requested_amount', function () {
    var total = 0;
    var parking_requested_amount = parseFloat($('.parking_requested_amount').val().replace(/,/g, ''));
    $('.parking_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val().replace(/,/g, ''));
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = parking_requested_amount ? total + val : '';
        $("#parking_total").val(Number(total).toLocaleString('en'));
    });
    if (total) {
        if ($(".parking_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Parking') === -1) {
                req_fields.push('Parking')
            }
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Parking';
            });
        }
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.car_requested_amount', function () {
    var total = 0;
    var car_requested_amount = parseFloat($('.car_requested_amount').val().replace(/,/g, ''));
    $('.car_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val().replace(/,/g, ''));
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = car_requested_amount ? total + val : '';
        $("#car_total").val(Number(total).toLocaleString('en'));
        var balance = $('#car_maintenance_balance').val().replace(/,/g, '');
        if (total > balance) {
            $('#w_car').text('You have exceeded the maximum amount limit');
            if (req_fields.indexOf('Car Maintenance') === -1) {
                req_fields.push('Car Maintenance')
            }
            $('#btn_benefits_reimbursement').prop('disabled', true);
        } else {
            $('#w_car').text('');
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Car Maintenance';
            });
            $('#btn_benefits_reimbursement').prop('disabled', false);
        }
    });
    if (total) {
        if ($(".car_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Car Maintenance') === -1) {
                req_fields.push('Car Maintenance')
            }
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Car Maintenance';
            });
        }
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.medical_requested_amount', function () {
    var total = 0;
    var medical_requested_amount = parseFloat($('.medical_requested_amount').val().replace(/,/g, ''));
    $('.medical_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val().replace(/,/g, ''));
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = medical_requested_amount ? total + val : '';
        $("#medical_total").val(Number(total).toLocaleString('en'));
        var balance = $('#medical_balance').val();
        if (total > balance) {
            $('#w_medical').text('You have exceeded the maximum amount limit');
            if (req_fields.indexOf('Medical') === -1) {
                req_fields.push('Medical')
            }
            $('#btn_benefits_reimbursement').prop('disabled', true);
        } else {
            $('#w_medical').text('');
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Medical';
            });
            $('#btn_benefits_reimbursement').prop('disabled', false);
        }
    });
    if (total) {
        if ($(".medical_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Medical') === -1) {
                req_fields.push('Medical')
            }
        }
    } else {
        req_fields = jQuery.grep(req_fields, function (value) {
            return value != 'Medical';
        });
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.gym_requested_amount', function () {
    var total = 0;
    var gym_requested_amount = parseFloat($('.gym_requested_amount').val());
    $('.gym_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val());
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = gym_requested_amount ? total + val : '';
        $("#gym_total").val(Number(total).toLocaleString('en'));
        var balance = $('#gym_balance').val();
        if (total > balance) {
            $('#w_gym').text('You have exceeded the maximum amount limit');
            if (req_fields.indexOf('Gym') === -1) {
                req_fields.push('Gym')
            }
            $('#btn_benefits_reimbursement').prop('disabled', true);
        } else {
            $('#w_gym').text('');
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Gym';
            });
            $('#btn_benefits_reimbursement').prop('disabled', false);
        }
    });
    if (total) {
        if ($(".gym_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Gym') === -1) {
                req_fields.push('Gym')
            }
        }
    } else {
        req_fields = jQuery.grep(req_fields, function (value) {
            return value != 'Gym';
        });
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.optical_requested_amount', function () {
    var total = 0;
    var optical_requested_amount = parseFloat($('.optical_requested_amount').val().replace(/,/g, ''));
    $('.optical_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val().replace(/,/g, ''));
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = optical_requested_amount ? total + val : '';
        $("#optical_total").val(Number(total).toLocaleString('en'));
        var balance = $('#optical_balance').val().replace(/,/g, '');
        if (total > balance) {
            $('#w_optical').text('You have exceeded the maximum amount limit');
            if (req_fields.indexOf('Optical') === -1) {
                req_fields.push('Optical')
            }
            $('#btn_benefits_reimbursement').prop('disabled', true);
        } else {
            $('#w_optical').text('');
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Optical';
            });
            $('#btn_benefits_reimbursement').prop('disabled', false);
        }
    });
    if (total) {
        if ($(".optical_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Optical') === -1) {
                req_fields.push('Optical')
            }
        }
    } else {
        req_fields = jQuery.grep(req_fields, function (value) {
            return value != 'Optical';
        });
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.cep_requested_amount', function () {
    var total = 0;
    var cep_requested_amount = parseFloat($('.cep_requested_amount').val());
    var type = $('#cep_type').val();
    var premise = $('#cep_premise').val();
    var cep_note = $('#cep_note');
    cep_note.text("");
    cep_balance = parseFloat($('#cep_balance').val());
    $(this).attr("max", cep_balance);
    $('.cep_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val());
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = cep_requested_amount ? total + val : '';
        $("#cep_total").val(Number(total).toLocaleString('en'));
        if (total != 0) {
            if (cep_balance < total) {
                cep_note.text("You have insufficient balance for this request");
            } else {
                if (type == "CEP" && premise == "Local") {
                    cep_note.text((total / 8000).toFixed(1) + " months bond");
                }
                if (type == "CEP" && premise == "International") {
                    cep_note.text((total / 15000).toFixed(1) + " months bond");
                }
                if (type == "Training" && premise == "Local") {
                    cep_note.text("");
                }
                if (type == "Training" && premise == "International") {
                    cep_note.text((total / 15000).toFixed(1) + " months bond");
                }
            }
        }
        var balance = $('#cep_balance').val();
        if (total > balance) {
            $('#btn_benefits_reimbursement').prop('disabled', true);
            if (req_fields.indexOf('CEP') === -1) {
                req_fields.push('CEP')
            }
        } else {
            $('#btn_benefits_reimbursement').prop('disabled', false);
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'CEP';
            });
        }
    });
    if (total) {
        if ($(".cep_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('CEP') === -1) {
                req_fields.push('CEP')
            }
        }
    } else {
        req_fields = jQuery.grep(req_fields, function (value) {
            return value != 'CEP';
        });
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.club_requested_amount', function () {
    var total = 0;
    var club_requested_amount = parseFloat($('.club_requested_amount').val());
    $('.club_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val());
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = club_requested_amount ? total + val : '';
        $("#club_total").val(Number(total).toLocaleString('en'));
    });
    if (total) {
        if ($(".club_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Club') === -1) {
                req_fields.push('Club')
            }
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Club';
            });
        }
    }
    $('.total_cat').trigger('change');
});
$(document).on('keyup change', '.maternity_requested_amount', function () {
    var total = 0;
    var maternity_requested_amount = parseFloat($('.maternity_requested_amount').val().replace(/,/g, ''));
    $('.maternity_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val().replace(/,/g, ''));
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = maternity_requested_amount ? total + val : '';
        $("#maternity_total").val(Number(total).toLocaleString('en'));
        var balance = $(this).data('max');
        if (total > balance) {
            $('#w_maternity').text('You have exceeded the maximum amount limit');
            if (req_fields.indexOf('Maternity') === -1) {
                req_fields.push('Maternity')
            }
            $('#btn_benefits_reimbursement').prop('disabled', true);
        } else {
            $('#w_maternity').text('');
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Maternity';
            });
            $('#btn_benefits_reimbursement').prop('disabled', false);
        }
    });
    if (total) {
        if ($(".maternity_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Maternity') === -1) {
                req_fields.push('Maternity')
            }
        }
    } else {
        req_fields = jQuery.grep(req_fields, function (value) {
            return value != 'Maternity';
        });
    }
    $('.total_cat').trigger('change');
});
// $(document).on('keyup change', '.maternity_requested_amount', function () {
//     var total = $(this).val();
//     max_val = $(this).data('max');
//     if (total > max_val) {
//         $('#w_maternity').text('Requested amount has exceeded the max value.');
//         $('#btn_benefits_reimbursement').prop('disabled', true);
//     } else {
//         $('#btn_benefits_reimbursement').prop('disabled', false);
//         $('#w_maternity').text('');
//         if (total == 0) {
//             $('#maternity_total').val(0);
//         } else {
//             $('#maternity_total').val(total);
//         }
//     }
//     if (total) {
//         if ($(".maternity_attachment")[0].files.length === 0) {
//             if (req_fields.indexOf('Maternity') === -1) {
//                 req_fields.push('Maternity')
//             }
//         } else {
//             req_fields = jQuery.grep(req_fields, function (value) {
//                 return value != 'Maternity';
//             });
//         }
//     }
//     $('.total_cat').trigger('change');
// });
$(document).on('keyup change', '.others_requested_amount', function () {
    var total = 0;
    var others_requested_amount = parseFloat($('.others_requested_amount').val());
    $('.others_requested_amount').each(function (index, element) {
        val = parseFloat($(element).val());
        if (isNaN(val)) {
            val = 0;
            $(element).val();
        }
        total = others_requested_amount ? total + val : '';
        $("#others_total").val(Number(total).toLocaleString('en'));
    });
    if (total) {
        if ($(".others_attachment")[0].files.length === 0) {
            if (req_fields.indexOf('Others') === -1) {
                req_fields.push('Others')
            }
        } else {
            req_fields = jQuery.grep(req_fields, function (value) {
                return value != 'Others';
            });
        }
    }
    $('.total_cat').trigger('change');
});
$('#parking_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_parking').prop('disabled', true);
    } else {
        $('.add_parking').prop('disabled', false);
    }
});
$('#gas_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_gas').prop('disabled', true);
    } else {
        $('.add_gas').prop('disabled', false);
    }
});
$('#car_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_car').prop('disabled', true);
    } else {
        $('.add_car').prop('disabled', false);
    }
});
$('#medical_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_medical').prop('disabled', true);
    } else {
        $('.add_medical ').prop('disabled', false);
    }
});
$('#gym_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_gym').prop('disabled', true);
    } else {
        $('.add_gym').prop('disabled', false);
    }
});
$('#optical_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_optical').prop('disabled', true);
    } else {
        $('.add_optical ').prop('disabled', false);
    }
});
$('#cep_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_cep').prop('disabled', true);
    } else {
        $('.add_cep ').prop('disabled', false);
    }
});
$('#club_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_club').prop('disabled', true);
    } else {
        $('.add_club ').prop('disabled', false);
    }
});
$('#maternity_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_maternity').prop('disabled', true);
    } else {
        $('.add_maternity ').prop('disabled', false);
    }
});
$('#others_requested_amount').keyup(function () {
    var val = parseFloat($(this).val().replace(/,/g, ''));
    if (isNaN(val)) {
        $('.add_others').prop('disabled', true);
    } else {
        $('.add_others ').prop('disabled', false);
    }
});
$('.total_cat').change(function () {
    var total = 0;
    $(".total_cat").each(function () {
        total += +parseFloat($(this).val().replace(/,/g, ''));
        $("#amount").val(total);
    });
});
$('#btn_benefits_reimbursement').click(function (e) {
    if (req_fields.length === 0) {
        return true;
    } else {
        e.preventDefault();
        var i;
        var cats = "";
        for (i = 0; i < req_fields.length; ++i) {
            cats += req_fields[i] + ', ';
        }
        cats = cats.slice(0, -2);
        alert("Please insert an attachment to the following categories: " + cats);
    }
});
$(document).on('click', '*[data-parking-id]', function () {
    var delete_parking_id = $(this).data('parking-id');
    var benefits_id = $(this).data('benefits-id');
    var amount = $(this).data('amount');
    $.ajax({
        type: "POST",
        url: "inc/config.php",
        data: {
            delete_parking_id: delete_parking_id,
            amount: amount,
            benefits_id: benefits_id
        },
        success: function (response) {
            alert('Parking data has been deleted.');
            $('#table-parking').html(response);
        }
    });
});
var tbody = $('#cep_tbody');
if (tbody.children().length == 0) {
    $('[name="update_cep_bond"]').prop('disabled', true);
}