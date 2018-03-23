function editable(line){
    var input = document.createElement("input")
    input.value= $(line).
}

$(document).ready(function(){
    $(".scope .btn.edit").click(function(e){
        e.preventDefault()
        var line = e.target.parent
        $(line).html()
    })
})