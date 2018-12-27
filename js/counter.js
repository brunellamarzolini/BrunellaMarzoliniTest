

function countOnType() {

    var c1 = document.getElementById('uno').value;
    var c2 = document.getElementById('due').value;
    document.getElementById("counter").innerHTML = c1.length + c2.length;

};


function alphabetPosition() {

    var c1 = document.getElementById('uno').value;
    var sum = 0;

    for (var i = 0; i < c1.length; ++i) {
        if(/^[a-zA-Z]*$/.test(c1.charAt(i)) == true){  //check if is a letter
            sum += c1.charAt(i).toLowerCase().charCodeAt(0) - 96;
        }
    }

    document.getElementById("alphabet").innerHTML = sum;

};



