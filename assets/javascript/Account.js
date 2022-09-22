$(document).ready(function(){

$(".nav-Item").click(function() {
    $(".nav-Item").each(function() {
         $(this).removeClass("active");
    });
    $(this).toggleClass("active");
  })
  
  $(".one").click(function(){
    $(".lkj").each(function() {
         $(this).addClass("d-none");
    });
    $(".col1").removeClass("d-none");
    $(".status").removeClass("d-none")
  })
  
  $(".two").click(function(){
    $(".lkj").each(function() {
         $(this).addClass("d-none");
    });
    $(".col2").removeClass("d-none");
    $(".status").addClass("d-none")
  })
  
  $(".three").click(function(){
    $(".lkj").each(function() {
         $(this).addClass("d-none");
    });
    $(".col3").removeClass("d-none");
    $(".status").addClass("d-none")
  })
})