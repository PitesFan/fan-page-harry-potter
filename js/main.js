const button = document.getElementById("header-btn");
button.addEventListener("click", function () {
  const element = document.getElementById("header-down");

  if (element.style.display == "none") {
    element.style.display = "block";
    element.style.display = "flex";
  } else {
    element.style.display = "none";
  }
});
