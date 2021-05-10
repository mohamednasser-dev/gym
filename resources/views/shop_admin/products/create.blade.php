@extends('shop_admin.app')
@section('title' , __('messages.add_product'))
@push('styles')
    <style>
        .wizard > .content > .body .select2-search input {
            border: none
        }
        input[disabled] {
            background-color: #eeeeee !important;
        }
        input[name="final_price[]"],
        input[name="total_amount[]"],
        input[name="remaining_amount[]"],
        input[name="barcodes[]"],
        input[name="stored_numbers[]"],
        input[disabled] {
            font-size: 10px
        }
        #properties-items .col-sm-5 {
            margin-bottom: 20px
        }
    </style>
@endpush
@push('scripts')
    <script>
        // inisialize multi select
        $(document).ready(function () {
            $(".multi_tags").select2({
                tags: true
            });
        })
        var language = "{{ Config::get('app.locale') }}",
            select = "{{ __('messages.select') }}"
        $("#category").on("change", function () {
            var categoryId = $(this).find("option:selected").val();

            $("#properties-items .row").html("")
            $.ajax({
                url: "/admin-panel/products/fetchcategoryoptions/" + categoryId,
                type: 'GET',
                success: function (data) {
                    $('#properties-items').show()

                    data.forEach(function (option) {
                        var optionName = option.title_en,
                            select = "{{ __('messages.select') }}",
                            anotherChoice = "{{ __('messages.another_choice') }}"
                        if (language == 'ar') {
                            optionName = option.title_ar
                        }
                        var propValOption = ""
                        propValOption += `
                        <option selected value="empty">
                            ${select}
                        </option>
                        `
                        option.values.forEach(function (propVal) {
                            var optionVal = propVal.value_en
                            if (language == 'ar') {
                                optionVal = propVal.value_ar
                            }
                            propValOption += `
                            <option value="${propVal.id}">
                                ${optionVal}
                            </option>
                            `
                        })
                        propValOption += `
                        <option value="0">
                            ${anotherChoice}
                        </option>`
                        var propValSelect = `
                        <select size="1" id="row-1-office" data-property="${option.id}" class="form-control properties-select" name="property_value_id[]">
                            ${propValOption}
                        </select>
                        `
                        $("#properties-items .row").append(`

                        <div class="col-sm-1">${optionName} <input type="hidden" value="${option.id}" name="option_id[]" /> <input type="hidden" name="another_option_en[]" /> <input type="hidden" name="another_option_ar[]" /></div>
                        <div class="col-sm-5">${propValSelect}</div>

                        `)
                    })
                }
            })
        })
        @if (isset($data['cat']))
        var categoryId = $("#category").find("option:selected").val()
        $.ajax({
            url: "/admin-panel/products/fetchcategoryoptions/" + categoryId,
            type: 'GET',
            success: function (data) {

                $('#properties-items').show()

                data.forEach(function (option) {

                    var optionName = option.title_en,
                        select = "{{ __('messages.select') }}",
                        anotherChoice = "{{ __('messages.another_choice') }}"
                    if (language == 'ar') {
                        optionName = option.title_ar
                    }
                    var propValOption = ""
                    propValOption += `
                        <option value="empty" selected>
                            ${select}
                        </option>
                        `
                    option.values.forEach(function (propVal) {
                        var optionVal = propVal.value_en
                        if (language == 'ar') {
                            optionVal = propVal.value_ar
                        }
                        propValOption += `
                            <option value="${propVal.id}">
                                ${optionVal}
                            </option>
                            `
                    })
                    propValOption += `
                        <option value="0">
                            ${anotherChoice}
                        </option>`
                    var propValSelect = `
                        <select size="1" id="row-1-office" data-property="${option.id}" class="form-control properties-select" name="property_value_id[]">
                            ${propValOption}
                        </select>
                        `
                    $("#properties-items .row").append(`
                        <div class="col-sm-1">${optionName} <input type="hidden" value="${option.id}" name="option_id[]" /> <input type="hidden" name="another_option_en[]" /> <input type="hidden" name="another_option_ar[]" /></div>
                        <div class="col-sm-5">${propValSelect}</div>
                        `)
                })
            }
        })
        @endif
        // action on checked discount
        $("#discount").click(function () {
            if ($(this).is(':checked')) {
                $("#offer_percentage").parent(".form-group").show()
                $("#offer_percentage").prop('disabled', false)
                if ($("#example tbody").children("tr").length > 0) {
                    $(".th-discount").show()
                    for (var n = 0; n < $("#example tbody").children("tr").length; n++) {
                        console.log($("#example tbody").children("tr").eq(n).children('td').eq(4).children('input').attr('name'))
                        $("#example tbody").children("tr").eq(n).children('td').eq(3).after(`
                            <td><input type="text" disabled class="form-control" > <input type="hidden" class="form-control" name="price_after_discount[]" ></td>
                            `)
                    }
                } else {
                    $("#final_price").parent(".form-group").show()
                }
            } else {
                $("#offer_percentage").parent(".form-group").hide()
                $("#offer_percentage").prop('disabled', true)
                if ($("#example tbody").children("tr").length > 0) {
                    $(".th-discount").hide()
                    for (var n = 0; n < $("#example tbody").children("tr").length; n++) {
                        $("#example tbody").children("tr").eq(n).children('td').eq(4).remove()
                    }
                } else {
                    $("#final_price").parent(".form-group").hide()
                }
            }
        })
        // add another option
        $("#properties-items .row").on('click', 'select', function () {
            var valEn = "{{ __('messages.value_en') }}",
                valAr = "{{ __('messages.value_ar') }}"
            if ($(this).val() == 0) {
                $(this).parent('.col-sm-5').prev('.col-sm-1').find("input[name='another_option_en[]']").prop('disabled', true)
                $(this).parent('.col-sm-5').prev('.col-sm-1').find("input[name='another_option_ar[]']").prop('disabled', true)
                $(this).siblings("input").remove()
                $(this).after(`<input style="margin-top:20px; border: 1px solid red" type="text" placeholder="${valEn}" name="another_option_en[]" class="form-control" >
                <input style="margin-top:20px; border: 1px solid red" type="text" placeholder="${valAr}" name="another_option_ar[]" class="form-control" >
                `)
            } else {
                $(this).parent('.col-sm-5').prev('.col-sm-1').find("input[name='another_option_en[]']").prop('disabled', false)
                $(this).parent('.col-sm-5').prev('.col-sm-1').find("input[name='another_option_ar[]']").prop('disabled', false)
                $(this).siblings("input").remove()
            }
        })

        // show price after discount
        $("#offer_percentage").on("keyup", function () {
            var discountValue = $("#offer_percentage").val(),
                price = $("#price_before_offer").val(),
                discountNumber = Number(price) * (Number(discountValue) / 100),
                total = Number(price) - discountNumber
            $("#final_price").val(total)

        })

        $("#price_before_offer").on("keyup", function () {
            var discountValue = $("#offer_percentage").val(),
                price = $("#price_before_offer").val(),
                discountNumber = Number(price) * (Number(discountValue) / 100),
                total = Number(price) - discountNumber
            $("#final_price").val(total)
        })

        $("#category_options .row").on('click', 'input', function () {
            var label = $(this).data("label"),
                labelEn = "English " + label,
                labelAr = "Arabic " + label,
                elementValue = $(this).val() + "element",
                optionId = $(this).val()

            if (language == 'ar') {
                labelEn = label + " باللغة الإنجليزية"
                labelAr = label + " باللغة العربية"
            }
            if ($(this).is(':checked')) {
                $("#category_options_sibling").append(`
                    <div class="form-group mb-4 ${elementValue}">
                        <label for="title_en">${labelEn}</label>
                        <input required type="text" name="value_en[]" class="form-control" id="title_en" placeholder="${labelEn}" value="" >
                    </div>
                    <div class="form-group mb-4 ${elementValue}">
                        <label for="title_en">${labelAr}</label>
                        <input required type="text" name="value_ar[]" class="form-control" id="title_en" placeholder="${labelAr}" value="" >
                    </div>
                    <input name="option[]" value="${optionId}" type="hidden" class="new-control-input ${elementValue}">
                    `)
            } else {
                console.log("." + elementValue)
                $("." + elementValue).remove()
            }
        })

        $("#add_home").on("change", function () {
            if ($(this).is(':checked')) {
                $("#home_section").prop("disabled", false)
                $("#home_section").parent(".form-group").show()
            } else {
                $("#home_section").prop("disabled", true)
                $("#home_section").parent(".form-group").hide()
            }
        })


        var previous = "{{ __('messages.previous') }}",
            next = "{{ __('messages.next') }}",
            finish = "{{ __('messages.finish') }}"

        // translate three buttons
        $(".actions ul").find('li').eq(0).children('a').text(previous)
        $(".actions ul").find('li').eq(1).children('a').text(next)
        $(".actions ul").find('li').eq(2).children('a').text(finish)

        // add class next1 to next button to control the first section
        $(".actions ul").find('li').eq(1).children('a').addClass("next1")

        // section one validation
        $(".actions ul").find('li').eq(1).on("mouseover", "a.next1", function () {
            var image = $('input[name="images[]"]').val(),
                categorySelect = $("#category").val(),
                subCategorySelect = $("#sub_category_select").val(),
                typeSelect = $("#typeSelect").val(),
                storeSelect = $("#storeSelect").val(),
                titleEnInput = $("input[name='title_en']").val(),
                titleArInput = $("input[name='title_ar']").val(),
                descriptionEnText = $('textarea[name="description_en"]').val(),
                descriptionArText = $('textarea[name="description_ar"]').val(),
                periodInput = $("#order_period").val()

            if (image.length > 0 && categorySelect > 0 && titleEnInput.length > 0 && titleArInput.length > 0 && descriptionEnText.length > 0 && descriptionArText.length > 0 && typeSelect > 0 && storeSelect > 0) {
                $(this).attr('href', '#next')
                $(this).addClass('next2')
            } else {
                $(this).attr('href', '#')
            }

        })

        // show validation messages on section 1
        $(".actions ul").find('li').eq(1).on("click", "a[href='#']", function () {
            var image = $('input[name="images[]"]').val(),
                categorySelect = $("#category").val(),
                subCategorySelect = $("#sub_category_select").val(),
                titleEnInput = $("input[name='title_en']").val(),
                titleArInput = $("input[name='title_ar']").val(),
                descriptionEnText = $('textarea[name="description_en"]').val(),
                descriptionArText = $('textarea[name="description_ar"]').val(),
                typeSelect = $("#typeSelect").val(),
                storeSelect = $("#storeSelect").val(),
                periodInput = $("#order_period").val(),
                imagesRequired = "{{ __('messages.images_required') }}",
                categoryRequired = "{{ __('messages.category_required') }}",
                subCategoryRequired = "{{ __('messages.sub_category_required') }}",
                titleEnRequired = "{{ __('messages.title_en_required') }}",
                titleArRequired = "{{ __('messages.title_ar_required') }}",
                descriptionEnRequired = "{{ __('messages.description_en_required') }}",
                descriptionArRequired = "{{ __('messages.description_ar_required') }}",
                typeRequired = "{{ __('messages.type_required') }}",
                storeRequired = "{{ __('messages.store_required') }}",
                periodRequired = "{{ __('messages.period_required') }}"

            if (image.length == 0) {
                if ($(".image-required").length) {

                } else {
                    $('input[name="images[]"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 image-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${imagesRequired}</div>
                        `)
                }
            } else {
                $(".image-required").remove()
            }

            if (typeSelect > 0) {
                $(".type-required").remove()
            } else {
                if ($(".type-required").length) {

                } else {
                    $("#typeSelect").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 type-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${typeRequired}</div>
                        `)
                }
            }

            if (storeSelect > 0) {
                $(".store-required").remove()
            } else {
                if ($(".store-required").length) {

                } else {
                    $("#storeSelect").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 store-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${storeRequired}</div>
                        `)
                }
            }

            if (categorySelect > 0) {
                $(".category-required").remove()
            } else {
                if ($(".category-required").length) {

                } else {
                    $("#category").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 category-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${categoryRequired}</div>
                        `)
                }
            }

            if (subCategorySelect > 0) {
                $(".sub-category-required").remove()
            } else {
                if ($(".sub-category-required").length) {

                } else {
                    $("#sub_category_select").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 sub-category-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${subCategoryRequired}</div>
                        `)
                }
            }

            if (titleEnInput.length == 0) {
                if ($(".titleEn-required").length) {

                } else {
                    $("input[name='title_en']").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 titleEn-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${titleEnRequired}</div>
                        `)
                }
            } else {
                $(".titleEn-required").remove()
            }

            if (titleArInput.length == 0) {
                if ($(".titleAr-required").length) {

                } else {
                    $("input[name='title_ar']").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 titleAr-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${titleArRequired}</div>
                        `)
                }
            } else {
                $(".titleAr-required").remove()
            }

            if (descriptionEnText.length == 0) {
                if ($(".descEn-required").length) {

                } else {
                    $('textarea[name="description_en"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 descEn-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${descriptionEnRequired}</div>
                        `)
                }
            } else {
                $(".descEn-required").remove()
            }

            if (descriptionArText.length == 0) {
                if ($(".descAr-required").length) {

                } else {
                    $('textarea[name="description_ar"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 descAr-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${descriptionArRequired}</div>
                        `)
                }
            } else {
                $(".descAr-required").remove()
            }
        })

        //section two | three | four validation
        $(".actions ul").find('li').eq(1).on('click', function () {
            var fieldRequired = "{{ __('messages.field_required') }}",
                remaininiLessTotal = "{{ __('messages.remaining_q_less_total') }}"

            //section two
            if ($(".steps ul").find('li').eq(1).hasClass('current')) {
                $("#properties-items").on("change", ".row .col-sm-5 select", function () {
                    if ($(this).val() == 0) {
                        $(this).attr('href', "#")
                        console.log("zero")
                        $(this).parent('.col-sm-5').on("keyup", "input", function () {

                            if ($(this).val().length > 0) {
                                $(this).css('border', '#CCC solid 1px')
                                $(this).attr('valid', '1')
                            } else {
                                $(this).css('border', 'red solid 1px')
                                $(this).attr('valid', '0')
                            }
                        })
                    } else {
                        console.log("int")
                        $(this).attr('href', "#next")
                    }
                })
                // validate if all inputs are not empty
                $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {

                    if ($("#properties-items .row").find(".col-sm-5 input[name='another_option_en[]']").length > 0) {
                        var countInputsEn = $("#properties-items .row").find(".col-sm-5 input[name='another_option_en[]']").length,
                            countInputsAr = $("#properties-items .row").find(".col-sm-5 input[name='another_option_ar[]']").length,
                            countInputs = Number(countInputsEn) + Number(countInputsAr),
                            countValid = $("#properties-items .row").find(".col-sm-5 input[valid='1']").length

                        if (countInputs != Number(countValid)) {
                            $(this).attr('href', "#")
                        } else {
                            $(this).attr('href', "#next")
                        }
                    }
                })
            }

            // section four
            if ($(".steps ul").find('li').eq(2).hasClass('current')) {
                if ($("#example tbody").find("tr").length > 0) {
                    for (var r = 0; r < $("#example tbody").find("tr").length; r++) {
                        console.log("inside big loop")
                        for (var q = 0; q < $("#example tbody").find("tr").eq(r).find("td").length; q++) {
                            console.log("inside tiny loop")
                            if (q != 0 && q != 4 && q != $("#example tbody").find("tr").eq(r).find("td").length - 1 && q != $("#example tbody").find("tr").eq(r).find("td").length - 2) {
                                console.log("wrong")
                                $("#example tbody").find("tr").eq(r).find("td").eq(q).on("keyup", "input", function () {

                                    if ($(this).attr('name') == 'remaining_amount[]') {
                                        var remainingVal = $(this).val(),
                                            totalVal = $(this).parent('td').prev('td').children('input').val()

                                        if (Number(remainingVal) <= Number(totalVal) && $(this).val()) {
                                            $(this).attr("placeholder", "")
                                            $(this).css("border", "#ccc solid 1px")
                                            $(this).attr("valid", "1")

                                        } else {
                                            if (Number(remainingVal) > Number(totalVal)) {
                                                $(this).attr("placeholder", remaininiLessTotal)
                                            } else {
                                                $(this).attr("placeholder", fieldRequired)
                                            }
                                            $(this).css("border", "red solid 1px")
                                            $(this).attr("valid", "0")
                                        }
                                    } else {
                                        if (!$(this).val()) {
                                            $(this).attr("placeholder", fieldRequired)
                                            $(this).css("border", "red solid 1px")
                                            $(this).attr("valid", "0")
                                        } else {
                                            $(this).attr("placeholder", "")
                                            $(this).css("border", "#ccc solid 1px")
                                            $(this).attr("valid", "1")
                                        }
                                    }
                                })

                            }

                            // validate barcode unique
                            if (q == $("#example tbody").find("tr").eq(r).find("td").length - 2) {
                                $("#example tbody").find("tr").eq(r).find("td").eq(q).on("keyup", "input", function () {
                                    var barcode = $(this).val(),
                                        ele = $(this)
                                    if (barcode) {
                                        $.ajax({
                                            url: "/admin-panel/products/validatebarcodeunique/barcode/" + barcode,
                                            type: 'GET',
                                            success: function (data) {
                                                if (data == 0) {
                                                    ele.css('border', '1px solid red')
                                                    ele.attr('unique', "0")
                                                } else {
                                                    ele.css('border', '#CCC solid 1px')
                                                    ele.attr('unique', "1")
                                                }
                                            }
                                        })
                                    }

                                })

                            }
                            // validate stored number unique
                            if (q == $("#example tbody").find("tr").eq(r).find("td").length - 1) {
                                $("#example tbody").find("tr").eq(r).find("td").eq(q).on("keyup", "input", function () {
                                    var stored = $(this).val(),
                                        ele = $(this)
                                    if (barcode) {
                                        $.ajax({
                                            url: "/admin-panel/products/validatebarcodeunique/stored/" + stored,
                                            type: 'GET',
                                            success: function (data) {
                                                if (data == 0) {
                                                    ele.css('border', '1px solid red')
                                                    ele.attr('unique', "0")
                                                } else {
                                                    ele.css('border', '#CCC solid 1px')
                                                    ele.attr('unique', "1")
                                                }
                                            }
                                        })
                                    }

                                })

                            }
                        }
                    }
                } else {
                    var totalQRequired = "{{ __('messages.total_quantity_required') }}",
                        remainingQRequired = "{{ __('messages.remaining_quantity_required') }}",
                        priceRequired = "{{ __('messages.price_required') }}",
                        offerRequired = "{{ __('messages.offer_required') }}",
                        remainingQLess = "{{ __('messages.remaining_q_less_total') }}"

                    console.log("simple shape")
                    $("input[name='total_quatity']").on('keyup', function () {
                        if (!$(this).val()) {
                            $(this).attr('valid', "0")
                            if ($(this).next('.offerV-required').length) {

                            } else {
                                $(this).after(`
                                    <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${totalQRequired}</div>
                                    `)
                            }
                        } else {
                            $(this).attr('valid', "1")
                            $(this).next('.offerV-required').remove()
                        }
                    })

                    $("input[name='remaining_quantity']").on('keyup', function () {
                        var remainingQ = $(this).val(),
                            totalQ = $("input[name='total_quatity']").val()
                        if (!$(this).val() || Number(remainingQ) > Number(totalQ)) {
                            $(this).attr('valid', "0")

                            if (Number(remainingQ) > Number(totalQ)) {
                                if ($(this).next('.offerV-required').length) {

                                } else {
                                    $(this).after(`
                                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${remainingQLess} ${totalQ}</div>
                                        `)
                                }
                            } else {
                                if ($(this).next('.offerV-required').length) {

                                } else {
                                    $(this).after(`
                                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${remainingQRequired}</div>
                                        `)
                                }
                            }

                        } else {
                            $(this).attr('valid', "1")
                            $(this).next('.offerV-required').remove()
                        }
                    })

                    $("input[name='price_before_offer']").on('keyup', function () {
                        if (!$(this).val()) {
                            $(this).attr('valid', "0")
                            if ($(this).next('.offerV-required').length) {

                            } else {
                                $(this).after(`
                                    <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${priceRequired}</div>
                                    `)
                            }
                        } else {
                            $(this).attr('valid', "1")
                            $(this).next('.offerV-required').remove()
                        }
                    })

                    $("#offer_percentage").on('keyup', function () {
                        if (!$(this).val()) {
                            $(this).attr('valid', "0")
                            if ($(this).next('.offerV-required').length) {

                            } else {
                                $(this).after(`
                                    <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${offerRequired}</div>
                                    `)
                            }
                        } else {
                            $(this).attr('valid', "1")
                            $(this).next('.offerV-required').remove()
                        }
                    })
                }

                // validation on click section 4
                $(".actions ul").find('li').eq(2).on("mouseover", "a", function () {
                    if ($("#example tbody").find("tr").length > 0) {
                        $(".actions ul").find('li').eq(2).find("a").attr('href', "#")
                        var inputsNumber = $("#example tbody").find("tr input").length - $("#example tbody").find("tr").length - (2 * $("#example tbody").find("tr").length)
                        var validInputs = $("#example tbody").find("tr input[valid='1']").length,
                            uniqueInputs = $("#example tbody").find("tr input[unique='1']").length,
                            offerValueRequired = "{{ __('messages.offerValueRequired') }}"


                        if ($("#discount").is(":checked")) {
                            inputsNumber = ($("#example tbody").find("tr input").length - $("#example tbody").find("tr").length) - (4 * $("#example tbody").find("tr").length)

                            $("input[name='offer_percentage']").on("keyup", function () {
                                if (!$(this).val()) {
                                    $(this).attr('valid', "0")
                                    if ($(".offerV-required").length) {

                                    } else {
                                        $(this).after(`
                                            <div style="margin-top:20px" class="alert alert-outline-danger mb-4 offerV-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${offerValueRequired}</div>
                                            `)
                                    }
                                } else {
                                    $(this).attr('valid', "1")
                                    $(this).next(".offerV-required").remove()
                                }

                            })

                            if (validInputs == inputsNumber) {

                                $(this).attr('href', "#finish")

                            }

                        } else {
                            if (validInputs == inputsNumber && uniqueInputs == (2 * $("#example tbody").find("tr").length)) {

                                $(this).attr('href', "#finish")

                            }
                        }

                    } else {

                        if ($("#discount").is(":checked")) {
                            if ($("input[name='price_before_offer']").attr('valid') == "1" &&
                                $("input[name='remaining_quantity']").attr('valid') == "1" &&
                                $("input[name='total_quatity']").attr('valid') == "1" &&
                                $("input[name='offer_percentage']").attr('valid') == "1") {
                                $(this).attr('href', "#finish")
                            } else {
                                $(this).attr('href', "#")
                            }
                        } else {
                            if ($("input[name='price_before_offer']").attr('valid') == "1" &&
                                $("input[name='remaining_quantity']").attr('valid') == "1" &&
                                $("input[name='total_quatity']").attr('valid') == "1") {
                                $(this).attr('href', "#finish")
                            } else {
                                $(this).attr('href', "#")
                            }
                        }
                    }

                })
            }
        })

        // on click prev
        $(".actions ul").find('li').eq(0).on("click", "a", function () {
            $("#multi_options_radio .row").on('change', 'input[type="radio"]', function () {
                if ($(this).val() != "none") {
                    $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', '#1b55e2')
                    $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', '#1b55e2')
                    $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").find('.select2-selection--multiple').css('border', '#bfc9d4 solid 1px')
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', '#e0e6ed')
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', '#e0e6ed')
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").find('.select2-selection--multiple').css('border', '#bfc9d4 solid 1px')
                    $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {
                        $(this).attr('href', "#")
                    })
                } else {
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', '#e0e6ed')
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', '#e0e6ed')
                    $("#multi_options_radio").find(".col-sm-4 select[disabled]").parent(".col-sm-4").find('.select2-selection--multiple').css('border', '#bfc9d4 solid 1px')
                    $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {
                        $(this).attr('href', "#next")
                    })
                }
                var multiId = $(this).data('multi')
                if ($(this).is(":checked")) {
                    $("select.multi_tags").prop('disabled', true)
                    $(`#${multiId}`).prop('disabled', false)
                    $(`#${multiId}`).prop('required', true)
                    $(`#${multiId}`).parent('.col-sm-4').siblings('.col-sm-4').children('select').prop('required', false)
                    $(`#${multiId}`).on("change", function () {
                        if ($(`#${multiId}`).val().length > 0) {
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', '#1b55e2')
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', '#1b55e2')
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").find('.select2-selection--multiple').css('border', '#bfc9d4 solid 1px')
                            $(`#${multiId}`).siblings('.offerV-required').remove()
                            $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {
                                $(this).attr('href', "#next")
                            })
                        } else {
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', 'red')
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', 'red')
                            $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").find('.select2-selection--multiple').css('border', 'red solid 1px')

                        }
                    })
                }
            })

            // on click make select none disabled with danger
            $(".actions ul").find('li').eq(1).on("click", "a", function () {
                if ($("#multi_options_radio").find(".col-sm-4").length > 0) {
                    if ($("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").length > 0 && $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").val().length == 0) {
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', 'red')
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', 'red')
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").find('.select2-selection--multiple').css('border', 'red solid 1px')
                    } else {
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-control-indicator').css('background', '#1b55e2')
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").prev('.col-sm-1').find('.new-chk-content').css('color', '#1b55e2')
                        $("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").parent(".col-sm-4").find('.select2-selection--multiple').css('border', '#bfc9d4 solid 1px')
                    }
                }
            })
            // if select empty prevent next
            if ($(".steps ul").find('li').eq(2).hasClass('current')) {
                if ($("#multi_options_radio").find(".col-sm-4 select:not(:disabled)").length > 0) {
                    $("#multi_options_radio").find(".col-sm-4").on("change", "select:not(:disabled)", function () {
                        if ($(this).val().length == 0) {
                            $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {
                                $(".actions ul").find('li').eq(1).find("a").attr('href', '#')
                            })

                        } else {
                            $(".actions ul").find('li').eq(1).on("mouseover", "a", function () {
                                $(".actions ul").find('li').eq(1).find("a").attr('href', '#next')
                            })
                        }
                    })
                }
            }
        })

        /*
        *  show / hide message on change value
        */

        // image
        $('input[name="images[]"]').on("change", function () {
            var image = $('input[name="images[]"]').val(),
                imagesRequired = "{{ __('messages.images_required') }}"

            if (image.length > 0) {
                if ($(".image-required").length) {
                    $(".image-required").remove()
                }
            } else {
                if ($(".image-required").length) {

                } else {
                    $('input[name="images[]"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 image-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${imagesRequired}</div>
                        `)
                }
            }
        })

        // Type
        $('#typeSelect').on("change", function () {
            var typeSelect = $(this).val(),
                typeRequired = "{{ __('messages.type_required') }}"

            if (typeSelect > 0) {
                if ($(".type-required").length) {
                    $(".type-required").remove()
                }
            } else {
                if ($(".type-required").length) {

                } else {
                    $('#typeSelect').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 type-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${typeRequired}</div>
                        `)
                }
            }
        })

        // Store
        $('#storeSelect').on("change", function () {
            var storeSelect = $(this).val(),
                storeRequired = "{{ __('messages.store_required') }}"

            if (storeSelect > 0) {
                if ($(".store-required").length) {
                    $(".store-required").remove()
                }
            } else {
                if ($(".store-required").length) {

                } else {
                    $('#storeSelect').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 store-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${storeRequired}</div>
                        `)
                }
            }
        })

        // category
        $("#category").on("change", function () {
            var categorySelect = $("#category").val(),
                categoryRequired = "{{ __('messages.category_required') }}"

            if (categorySelect > 0) {
                if ($(".category-required").length) {
                    $(".category-required").remove()
                }
            } else {
                if ($(".category-required").length) {

                } else {
                    $("#category").after(`
                            <div style="margin-top:20px" class="alert alert-outline-danger mb-4 category-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${categoryRequired}</div>
                            `)
                }
            }
        })

        // sub category
        $("#sub_category_select").on("change", function () {
            var subCategorySelect = $("#sub_category_select").val(),
                subCategoryRequired = "{{ __('messages.sub_category_required') }}"

            if (subCategorySelect > 0) {
                if ($(".sub-category-required").length) {
                    $(".sub-category-required").remove()
                }
            } else {
                if ($(".sub-category-required").length) {

                } else {
                    $("#sub_category_select").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 sub-category-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${subCategoryRequired}</div>
                        `)
                }
            }
        })

        // title en
        $("input[name='title_en']").on("keyup", function () {
            var titleEnInput = $("input[name='title_en']").val(),
                titleEnRequired = "{{ __('messages.title_en_required') }}"

            if (titleEnInput.length > 0) {
                if ($(".titleEn-required").length) {
                    $(".titleEn-required").remove()
                }
            } else {
                if ($(".titleEn-required").length) {

                } else {
                    $("input[name='title_en']").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 titleEn-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${titleEnRequired}</div>
                        `)
                }
            }
        })

        // title ar
        $("input[name='title_ar']").on("keyup", function () {
            var titleArInput = $("input[name='title_ar']").val(),
                titleArRequired = "{{ __('messages.title_ar_required') }}"

            if (titleArInput.length > 0) {
                if ($(".titleAr-required").length) {
                    $(".titleAr-required").remove()
                }
            } else {
                if ($(".titleAr-required").length) {

                } else {
                    $("input[name='title_ar']").after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 titleAr-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${titleArRequired}</div>
                        `)
                }
            }
        })

        // description en
        $('textarea[name="description_en"]').on("keyup", function () {
            var descriptionEnText = $('textarea[name="description_en"]').val(),
                descriptionEnRequired = "{{ __('messages.description_en_required') }}"

            if (descriptionEnText.length > 0) {
                if ($(".descEn-required").length) {
                    $(".descEn-required").remove()
                }
            } else {
                if ($(".descEn-required").length) {

                } else {
                    $('textarea[name="description_en"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 descEn-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${descriptionEnRequired}</div>
                        `)
                }
            }
        })

        // description ar
        $('textarea[name="description_ar"]').on("keyup", function () {
            var descriptionArText = $('textarea[name="description_ar"]').val(),
                descriptionArRequired = "{{ __('messages.description_ar_required') }}"

            if (descriptionArText.length > 0) {
                if ($(".descAr-required").length) {
                    $(".descAr-required").remove()
                }
            } else {
                if ($(".descAr-required").length) {

                } else {
                    $('textarea[name="description_ar"]').after(`
                        <div style="margin-top:20px" class="alert alert-outline-danger mb-4 descAr-required" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><i class="flaticon-cancel-12 close" data-dismiss="alert"></i> ${descriptionArRequired}</div>
                        `)
                }
            }
        })


        // submit form on click finish
        $(".actions ul").find('li').eq(2).on("click", 'a[href="#finish"]', function () {
            $("form").submit()
        })

    </script>


@endpush

@section('content')

    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_product') }}</h4>
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-unstyled mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <div id="circle-basic" class="">
                                <h3>{{ __('messages.product_details') }}</h3>
                                <section>
                                    <div class="custom-file-container" data-upload-id="myFirstImage">
                                        <label>{{ __('messages.upload') }} ({{ __('messages.multiple_image') }}) * <a
                                                href="javascript:void(0)" class="custom-file-container__image-clear"
                                                title="Clear Image">x</a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file" required name="images[]" multiple
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                   accept="image/*">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                                            <span
                                                class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeSelect">{{ __('messages.store') }} *</label>
                                        <select required id="storeSelect" name="store_id" class="form-control">
                                            <option value="0" selected>{{ __('messages.select') }}</option>
                                            @if(count($data['stores']) > 0)
                                                @foreach ($data['stores'] as $store)
                                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="typeSelect">{{ __('messages.type') }} *</label>
                                        <select required id="typeSelect" name="type" class="form-control">
                                            <option value="0" selected>{{ __('messages.select') }}</option>
                                            @if(count($data['types']) > 0)
                                                @foreach ($data['types'] as $type)
                                                    <option
                                                        value="{{ $type->id }}">{{ App::isLocale('en') ? $type->type_en : $type->type_ar }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">{{ __('messages.category') }} *</label>
                                        <select id="category" name="category_id" class="form-control">
                                            <option selected>{{ __('messages.select') }}</option>
                                            @foreach ( $data['categories'] as $category )
                                                <option
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }} {{ isset($data['cat']) && $data['cat']['id'] == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ App::isLocale('en') ? $category->title_en : $category->title_ar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="title_en">{{ __('messages.title_en') }} *</label>
                                        <input required type="text" name="title_en" class="form-control" id="title_en"
                                               placeholder="{{ __('messages.title_en') }}"
                                               value="{{ old('title_en') }}">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="title_ar">{{ __('messages.title_ar') }} *</label>
                                        <input required type="text" name="title_ar" class="form-control" id="title_ar"
                                               placeholder="{{ __('messages.title_ar') }}"
                                               value="{{ old('title_ar') }}">
                                    </div>
                                    <div class="form-group mb-4 arabic-direction">
                                        <label for="demo2">{{ __('messages.desc_ar') }} *</label>
                                        <textarea name="description_ar" required class="form-control"
                                                  rows="5">{{ old('description_ar') }}</textarea>
                                    </div>
                                    <div class="form-group mb-4 english-direction">
                                        <label for="demo1">{{ __('messages.desc_en') }} *</label>
                                        <textarea required name="description_en" class="form-control"
                                                  rows="5">{{ old('description_en') }}</textarea>
                                    </div>
                                </section>
                                <h3>{{ __('messages.product_specification') }} ( {{ __('messages.optional') }} )</h3>
                                <section>
                                    <div id="category_options" style="margin-bottom: 20px" class="col-md-3">
                                        <label> {{ __('messages.properties') }} </label>
                                    </div>

                                    <div style="display: none" id="properties-items" class="table table-hover"
                                         style="width:100%">
                                        <div class="row">
                                        </div>
                                    </div>
                                </section>
                                <h3>{{ __('messages.prices_and_inventory') }}</h3>
                                <section>
                                    <div id="single-details">
                                        <div class="form-group mb-4">
                                            <label for="total_quatity">{{ __('messages.total_quatity') }} *</label>
                                            <input required type="number" name="total_quatity" class="form-control"
                                                   id="total_quatity" placeholder="{{ __('messages.total_quatity') }}"
                                                   value="{{ old('total_quatity') }}">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="remaining_quantity">{{ __('messages.remaining_quantity') }}
                                                *</label>
                                            <input required type="number" name="remaining_quantity" class="form-control"
                                                   id="remaining_quantity"
                                                   placeholder="{{ __('messages.remaining_quantity') }}"
                                                   value="{{ old('remaining_quantity') }}">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="price_before_offer">{{ __('messages.product_price') }} *</label>
                                            <input required type="number" step="any" min="0" name="price_before_offer"
                                                   class="form-control" id="price_before_offer"
                                                   placeholder="{{ __('messages.product_price') }}"
                                                   value="{{ old('price_before_offer') }}">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label
                                                for="stored_number">{{ __('messages.product_stored_number') }}</label>
                                            <input type="text" name="stored_number" class="form-control"
                                                   id="stored_number"
                                                   placeholder="{{ __('messages.product_stored_number') }}" value="">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="barcode">{{ __('messages.barcode') }}</label>
                                            <input type="text" name="barcode" class="form-control" id="barcode"
                                                   placeholder="{{ __('messages.barcode') }}">
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 20px; margin-top : 20px" class="col-md-3">
                                        <div>
                                            <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                                <input id="discount" name="offer" value="1" type="checkbox"
                                                       class="new-control-input">
                                                <span class="new-control-indicator"></span><span
                                                    class="new-chk-content">{{ __('messages.discount') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:none" class="form-group mb-4">
                                        <label for="offer_percentage">{{ __('messages.discount_value') }} ( % )</label>
                                        <input disabled type="number" step="any" min="0" name="offer_percentage"
                                               class="form-control" id="offer_percentage"
                                               placeholder="{{ __('messages.discount_value') }}" value="">
                                    </div>
                                    <div id="single-discount">
                                        <div style="display:none" class="form-group mb-4">
                                            <label for="final_price">{{ __('messages.price_after_discount') }}</label>
                                            <input style="font-size: 15px" disabled type="number" step="any" min="0"
                                                   name="final_price" class="form-control" id="final_price"
                                                   placeholder="{{ __('messages.price_after_discount') }}" value="">
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
