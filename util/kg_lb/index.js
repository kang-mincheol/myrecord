function numberKeyUp(e) {
    console.log(e);
    var thisValue = e.target.value;

    if(thisValue.charAt(0) == '0') {
        thisValue = thisValue.substr(1, thisValue.length);
    }

    e.target.value = thisValue;

    var thisType = "kg";
    var mirrorType = "lb";
    if(e.target.attributes[2]["nodeValue"] != thisType) {
        thisType = "lb";
        mirrorType = "kg";
    }

    var calcValue = 0;
    var relativeValue = 2.205;
    if(thisType == "kg") {
        calcValue = thisValue * relativeValue;
    } else {
        calcValue = thisValue / relativeValue;
    }

    if(!Number.isInteger(calcValue)) {
        calcValue = calcValue.toFixed(2);
    }

    $(".calc_value[name="+mirrorType+"]").val(calcValue);
}