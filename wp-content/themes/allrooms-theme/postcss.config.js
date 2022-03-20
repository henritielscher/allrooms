const currentTask = process.env.npm_lifecycle_event;

let postcssPlugins = [
  require("postcss-preset-env"),
  // require("tailwindcss"),
  require("autoprefixer"),
];

if (currentTask == "build") {
  postcssPlugins.push(require("cssnano"));
}

module.exports = {
  plugins: postcssPlugins,
};
