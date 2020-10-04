// Configuration for r.js optimizer
({
    baseUrl: '.',
    mainConfigFile: 'main.js',
    name: "main",
    out: "main-minified.js",
    include: ['css',
              'async',
              'knockout',
              'wjr/event-list',
              'wjr/flickr-photos',
              'wjr/result-list',
              'wjr/map',
              'wjr/wjr',
              'wjr/iof',
              'wjr/binding']
})
