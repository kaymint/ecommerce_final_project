/**
 * Created by StreetHustling on 4/2/16.
 */
/*global $ */
$(document).ready(function (e) {
    'use strict';

    $( "#fName" ).keyup(function() {

        var fullname = $(this).val();
        var testfullname = validateName(fullname);

        if(testfullname === true){
            $( this ).css( "background-color", "#F0FFF0" );
        }else{
            $( this ).css( "background-color", "#FFAEB9" );
        }
    });

    $( "#lName" ).keyup(function() {
        var fullname = $(this).val();
        var testfullname = validateName(fullname);

        if(testfullname === true){
            $( this ).css( "background-color", "#F0FFF0" );
        }else{
            $( this ).css( "background-color", "#FFAEB9" );
        }
    });

    $( "#email" ).keyup(function() {
        var email = $(this).val();
        var testfullname = validateEmail(email);

        if(testfullname === true){
            $( this ).css( "background-color", "#F0FFF0" );
        }else{
            $( this ).css( "background-color", "#FFAEB9" );
        }
    });


    $( "#phone" ).keyup(function() {
        var phone = $(this).val();
        var testPhone = validatePhone(phone);

        if(testPhone === true){
            $( this ).css( "background-color", "#F0FFF0" );
        }else{
            $( this ).css( "background-color", "#FFAEB9" );
        }
    });

    function validateName(input){

        var fullname = /^[A-Z]([-']?[a-z]+)/;
        return fullname.test(input);
    }

    function validateEmail(input){
        var email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return email.test(input);
    }


    function validatePhone(input){
        var phone = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
        return phone.test(input);
    }


});
