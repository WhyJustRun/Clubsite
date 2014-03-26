({
    baseUrl: '.',
    mainConfigFile: 'main.js',
    name: "main",
    out: "main-built.js",
    include: ['domReady',
              'css',
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
