$(document).ready(function() {    
    $().ready(function() {
        var elf = $('#elfinder').elfinder({
            lang: 'hu',             // language (OPTIONAL)
            url : 'public/admin_assets/plugins/elfinder/php/connector.php',  // connector URL (REQUIRED)
            uiOptions : {
                // toolbar configuration
                toolbar : [
                    ['back', 'forward'],
                    // ['reload'],
                    ['home', 'up'],
                    ['mkdir', 'upload'],
                    ['open', 'download', 'getfile'],
                    ['info'],
                    ['quicklook'],
                    ['copy', 'cut', 'paste'],
                    ['rm'],
                    ['duplicate', 'rename', 'edit', 'resize'],
                    // ['extract', 'archive'],
                    ['search'],
                    ['view']
                    // ['help']
                ],

                // directories tree options
                tree : {
                    // expand current root on init
                    openRootOnLoad : true,
                    // auto load current dir parents
                    syncTree : true
                },

                // navbar options
                navbar : {
                    minWidth : 150,
                    maxWidth : 500
                },

                // current working directory options
                cwd : {
                    // display parent directory in listing as ".."
                    oldSchool : false
                }
            }
            
        }).elfinder('instance');            
    });

});