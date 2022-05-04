async function init() {
  wiloke.loadStyle({
    file: "https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.2.0/swiper-bundle.min.css",
  });
  await wiloke.loadScript({
    file: "https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.2.0/swiper-bundle.min.js",
  });
  wiloke.elementor(".wil-swiper", wiloke.swiper);
}

if (window.wiloke) {
  init();
} else {
  const coreJs = "https://envato-element-js-core.netlify.app/main.js";
  const coreJsEl = document.createElement("script");
  coreJsEl.src = coreJs;
  document.body.appendChild(coreJsEl);
  coreJsEl.addEventListener("load", init);
}
