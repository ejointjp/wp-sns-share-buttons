/////////////////////////////////////////////////////////////////////////////////////

// requirement

/////////////////////////////////////////////////////////////////////////////////////

var gulp = require('gulp')
var del = require('del')
var path = require('path')
var argv = require('minimist')(process.argv.slice(2))
// var runSequence = require('run-sequence')

/////////////////////////////////////////////////////////////////////////////////////

// config

/////////////////////////////////////////////////////////////////////////////////////

var config = require('./_config')
var dir = config.dir
var file = config.file
// const ProjectRoot = dir.root

/////////////////////////////////////////////////////////////////////////////////////

// tasks

/////////////////////////////////////////////////////////////////////////////////////

// clean assets file
gulp.task('clean', function () {
  var assets = path.join(dir.rel.dist, dir.assets, dir.css, file.all)
  var caches = path.join(dir.rel.dist, dir.cache, dir.all, file.all)
  var deleteFiles = [assets]

  if(argv.cache) {
    deleteFiles = [caches]
  }

  if(argv.all) {
    deleteFiles = [assets, caches]
  }

  return del(deleteFiles).then(function (paths) {
    console.log('Deleted files: \n', paths.join('\n'))
  })
})
