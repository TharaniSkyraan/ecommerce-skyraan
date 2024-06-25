const body = document.querySelector("body");
const darkLight = document.querySelector("#darkLight");
const sidebar = document.querySelector(".sidebar");
const mainbody = document.querySelector(".main-container");
const submenuItems = document.querySelectorAll(".submenu_item");
const sidebarOpen = document.querySelector("#sidebarOpen");
const sidebarClose = document.querySelector(".collapse_sidebar");
const sidebarExpand = document.querySelector(".expand_sidebar");
const bottomContent = document.querySelector(".bottom_content");
sidebarOpen.addEventListener("click", () => sidebar.classList.toggle("close"));

sidebarClose.addEventListener("click", () => {
  sidebar.classList.add("close", "hoverable");
  mainbody.classList.add("main-container-res");
});
sidebarExpand.addEventListener("click", () => {
  sidebar.classList.remove("close", "hoverable");
  mainbody.classList.remove("main-container-res");
});

// sidebar.addEventListener("mouseenter", () => {
//   if (sidebar.classList.contains("hoverable")) {
//     sidebar.classList.remove("close");
//   }
// });
// sidebar.addEventListener("mouseleave", () => {
//   if (sidebar.classList.contains("hoverable")) {
//     sidebar.classList.add("close");
//   }
// });

darkLight.addEventListener("click", () => {
  body.classList.toggle("dark");
  if (body.classList.contains("dark")) {
    document.setI;
    darkLight.classList.replace("bx-sun", "bx-moon");
  } else {
    darkLight.classList.replace("bx-moon", "bx-sun");
  }
});

submenuItems.forEach((item, index) => {
  item.addEventListener("click", () => {
    item.classList.toggle("show_submenu");
    // submenuItems.forEach((item2, index2) => {
    //   if (index !== index2) {
    //     item2.classList.remove("show_submenu");
    //   }
    // });
  });
});

if (window.innerWidth < 768) {
  sidebar.classList.add("close");
  bottomContent.classList.add("d-none");
} else {
  sidebar.classList.remove("close");
  bottomContent.classList.remove("d-none");
  
}
