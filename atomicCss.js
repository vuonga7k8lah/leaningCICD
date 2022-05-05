const { atomic } = require("mota-css");
const config = require("./src/config.json");

atomic.setConfig({
  breakpoints: {
    sm: "768px",
    md: "992px",
    lg: "1200px",
  },
  parentSelector: `.${config.name}-wrapper`,
  defaultCss: "",
  custom: {
    "color-primary": "var(--color-primary)",
    "color-secondary": "var(--color-secondary)",
    "color-tertiary": "var(--color-tertiary)",
  },
});

atomic.customValue((value) => {
  const regexp = /\.\d*/g;
  if (regexp.test(value) && value.includes("color")) {
    const val = value.replace(regexp, "");
    const alpha = value.replace(/.*(?=\.\d*)/g, "");
    return `rgba(var(--rgb-${val}), ${alpha})`;
  }
  return value;
});

exports.setAtomicCss = (str) => {
  atomic.find(str);
};

exports.getAtomicCss = () => {
  const css = atomic.getCss();

  return css;
};
//coder ngao 123213a21231sdasdasddasdassdasdassdasdassdasdsdsadsasdasd