const gulp = require("gulp");
const browserSync = require("browser-sync").create();
const sourcemaps = require("gulp-sourcemaps");
const fs = require("fs-extra");
const twig = require("gulp-twig");
const glob = require("glob");
const { getAtomicCss, setAtomicCss } = require("./atomicCss");
const watch = require("node-watch");
const sass = require("sass");
const { atomic } = require("mota-css");
const configure = require("./src/config.json");
const { default: postcss } = require("postcss");
const cssnano = require("cssnano");
const { config } = require("./server-config");

const isDev = process.env.NODE_ENV === "development";
let watcher = watch(config.input, { recursive: true });
const output = isDev ? config.output.dev : config.output.prod;

const getCssPattern = (cssVar) =>
  new RegExp(`${cssVar}\`.*([\\s\\S]*?)\``, "g");

const handleTwigFile = (file, run) => {
  try {
    const content = fs.readFileSync(file, "utf8").toString();
    if (!/set\s+componentName/g.test(content)) {
      fs.writeFileSync(
        file,
        `{% set componentName = "${configure.name}" %}\n${content}`
      );
    } else {
      if (run) {
        fs.writeFileSync(
          file,
          content.replace(
            /(\{%\s+set\s+componentName\s+=\s+["'])([\w-]*)(.*)/g,
            `$1${configure.name}$3`
          )
        );
      }
    }
    setAtomicCss(content);
  } catch (err) {
    console.log(err);
  }
};

const handleJsFile = (file) => {
  try {
    const content = fs.readFileSync(file).toString();
    setAtomicCss(content);
  } catch (err) {
    console.log(err);
  }
};

const handleScssFile = (file) => {
  const scssContent = fs.readFileSync(file).toString();
  const setCss = atomic.createCssServer(file);
  setCss(
    sass.compileString(
      `$builderMode: true;
      $componentName: ${configure.name};
      ${scssContent.trim()}\n`
    ).css
  );
};

function objectParse(value) {
  const fn = new Function(`return ${value.trim()}`);
  return fn();
}

const getType = (field) => {
  if (field.type === "icons") {
    return "string | { url: string };";
  }
  if (field.type === "media") {
    return "string;";
  }
  if (Array.isArray(typeof field.default)) {
    return "any[];";
  }
  if (typeof field.default === "object") {
    return "Record<string, any>;";
  }
  return `${typeof field.default};`;
};

const genDataType = (schema) => {
  return `{\n${schema.reduce((str, field) => {
    if (field.fields) {
      return `${str}\n${field.name}: ${
        field.type === "array"
          ? `${genDataType(field.fields)}[];`
          : genDataType(field.fields)
      }`.trim();
    }
    return `${str}\n${field.name}: ${getType(field)}`.trim();
  }, "")}\n};`;
};

const handleSchemaFile = (file) => {
  const content = fs.readFileSync(file, "utf8").toString();
  let schema = objectParse(
    content.replace(/.*([\s\S]*?):\s+Schema\s+=\s+/g, "")
  );
  fs.writeFileSync(
    `${config.input}/typegen.ts`,
    `export type Data = ${genDataType(schema).replace(/;\[\]/g, "[]")}`
  );
};

/**
 * It compiles all the HTML files in the output directory and then compiles the CSS files in the output
 * directory.
 */
function atomicCss() {
  const writeFile = (file) => {
    if (!!file) {
      if (file.includes("schema.ts")) {
        handleSchemaFile(file);
      }
      if (file.includes(".twig")) {
        handleTwigFile(file);
      }
      if (file.includes(".js")) {
        handleJsFile(file);
      }
      if (file.includes(".scss")) {
        handleScssFile(file);
      }
    } else {
      const twigFiles = glob.sync(`${config.input}/**/*.twig`);
      for (let i = 0; i < twigFiles.length; i++) {
        const file = twigFiles[i];
        handleTwigFile(file, true);
      }
      const jsFiles = glob.sync(`${config.input}/**/*.js`);
      for (let i = 0; i < jsFiles.length; i++) {
        const file = jsFiles[i];
        handleJsFile(file);
      }
      const scssFiles = glob.sync(`${config.input}/**/*.scss`);
      for (let i = 0; i < scssFiles.length; i++) {
        const file = scssFiles[i];
        handleScssFile(file);
      }
      handleSchemaFile(`${config.input}/schema.ts`);
    }
    const css = getAtomicCss();
    try {
      if (fs.existsSync(output)) {
        if (!fs.existsSync(`${output}/${config.styles}`)) {
          fs.mkdirSync(`${output}/${config.styles}`);
        }
        if (isDev) {
          fs.writeFileSync(`${output}/${config.styles}/styles.css`, css);
          if (file?.includes(".scss")) {
            browserSync.reload();
          }
        } else {
          const processor = postcss([cssnano()]);
          processor.process(css, { from: undefined }).then((result) => {
            const { css = "" } = result;
            fs.writeFileSync(`${output}/${config.styles}/styles.css`, css);
          });
        }
      }
    } catch (err) {
      console.log(err);
    }
  };
  writeFile();
  watcher.on("change", (evt, file) => {
    if (/\.(twig|liquid|scss|js|ts)$/g.test(file)) {
      writeFile(file);
    }
  });
}

/**
 * It reads all the JSON files in the input directory and returns a dictionary of the contents of those
 * files
 * @returns A JavaScript object with the contents of each JSON file in the input directory.
 */
function getJSON() {
  const files = glob.sync(`${config.input}/**/*.data.ts`);
  const contents = files.reduce(
    (obj, file) => {
      const fileName = file.replace(/.*\/|\.data\.ts/g, "");
      const content =
        fs
          .readFileSync(file)
          .toString()
          .replace(/import.*/g, "")
          .replace(/export.*: Data\s*=/g, "return") || "{}";
      const fn = new Function(content.trim());
      return {
        ...obj,
        [fileName]: fn(),
      };
    },
    { builderMode: true }
  );
  return contents;
}

/**
 * Compiles all the HTML files in the src directory and puts them in the build directory
 * @param cb - A callback function that runs after the task has completed.
 */
function compileTwig(cb) {
  try {
    gulp
      .src(`${config.input}/*.twig`)
      .pipe(
        twig({ data: getJSON(), base: "./src/Views", errorLogToConsole: true })
      )
      .pipe(gulp.dest(output))
      .pipe(browserSync.stream());
  } catch (err) {
    console.log(err);
  }
  cb();
}

/**
 * It takes all the JavaScript files in the src/js folder and compiles them into the build/js folder
 */
function compileJs(cb) {
  gulp
    .src(`${config.input}/${config.js}/*.js`)
    .pipe(
      gulp.dest(
        isDev
          ? `${config.output.dev}/${config.js}`
          : `${config.output.prod}/${config.js}`
      )
    );
  cb();
}

/**
 * Copy all images from the src/img directory to the build/img directory
 * @param cb - A callback function that runs after the task has completed.
 */
function copyImages(cb) {
  gulp
    .src(`${config.input}/${config.img}/**/*`)
    .pipe(
      gulp.dest(
        isDev
          ? `${config.output.dev}/${config.img}`
          : `${config.output.prod}/${config.img}`
      )
    );
  cb();
}

/**
 * Watch the files in the src/scss folder and when they change, run the compileScss function
 * @param cb - A callback function that runs after the watch task is complete.
 */
function watchFiles() {
  try {
    gulp.watch(`${config.input}/${config.img}/**/*`, copyImages);
    gulp.watch(`${config.input}/**/*.js`, (done) => {
      compileJs(done);
      compileTwig(done);
    });
    gulp.watch(`${config.input}/**/*.ts`, (done) => {
      compileJs(done);
      compileTwig(done);
    });
    gulp.watch(`${config.input}/**/*.twig`, compileTwig);
    gulp.watch(`${config.input}/**/*.json`, compileTwig);
  } catch (err) {
    console.log(err);
  }
}

/**
 * Start the browserSync server and watch the files in the build folder
 * @param cb - A callback function that runs after the browserSync instance is initialized.
 */
function handleBrowserSync(cb) {
  browserSync.init({
    port: config.port,
    server: {
      baseDir: output,
    },
    logConnections: true,
    logFileChanges: true,
    notify: true,
    open: isDev,
  });
  watchFiles();
  cb();
}

function taskAtomicCss(cb) {
  const timeId = setTimeout(() => {
    atomicCss();
    cb();
    clearTimeout(timeId);
  }, 1000);
}

/**
 * This function is a gulp task that runs the following tasks in parallel:
 *
 * handleBrowserSync
 * compileScss
 * compileTwig
 * copyImages
 * @returns The dev task is returning the handleBrowserSync function, which is being returned from the
 * gulp.series function.
 */
function dev() {
  fs.removeSync(config.output.dev);
  return gulp.series(
    handleBrowserSync,
    gulp.parallel(
      gulp.series(compileTwig, compileJs, taskAtomicCss),
      copyImages
    )
  );
}

const handleWatcherClose = (cb) => {
  watcher.close();
  cb();
};

/**
 * Build the project by running the following tasks in parallel:
 *
 * Compile the Sass files.
 * Compile the HTML files.
 * Copy the images
 * @returns The build function returns a gulp.parallel task.
 */
function build() {
  fs.removeSync(config.output.prod);
  return gulp.parallel(
    gulp.series(compileTwig, compileJs, taskAtomicCss, handleWatcherClose),
    copyImages
  );
}

gulp.task("dev", dev());
gulp.task("build", build());
