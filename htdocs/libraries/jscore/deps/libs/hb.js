define(['text', 'handlebars'], function(text, handlebars) {

    var buildCache = {},
        buildCompileTemplate = 'define("{{pluginName}}!{{moduleName}}", ["handlebars"], function(handlebars) {return handlebars.template({{{fn}}})});',
        buildTemplate;

    var load = function(moduleName, parentRequire, load, config) {

        text.get(parentRequire.toUrl(moduleName), function(data) {

            if(config.isBuild) {
                buildCache[moduleName] = data;
                load();
            } else {
                load(handlebars.compile(data));
            }
        });
    };

    var write = function(pluginName, moduleName, write) {

        if(moduleName in buildCache) {

            if(!buildTemplate) {
                buildTemplate = handlebars.compile(buildCompileTemplate);
            }

            write(buildTemplate({
                pluginName: pluginName,
                moduleName: moduleName,
                fn: handlebars.precompile(buildCache[moduleName])
            }));
        }
    };

    return {
        load: load,
        write: write
    };
});