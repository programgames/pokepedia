const $ = require('jquery');
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

let pokemons = [];
let learnMethod = '';
let generation = [];
let pokemonIndex;
let generationIndex;
let maxPokemon;
let maxGeneration;
const compareDiv = document.getElementById('compare-div');
const configuration = {
    drawFileList: false,
    matching: 'lines',
    fileListToggle: false,
    fileListStartVisible: false,
    fileContentToggle: false,
    outputFormat: 'side-by-side',
};

let section;
let title;
let wikiText;
let logs = $('#logs-compare');
let uploadButton = $('#upload-compare');
let nextButton = $('#next-compare');
let clearCacheButton = $('#clear-cache');

clearCacheButton.click(function () {

    $.ajax
    ({
        url: Routing.generate('_clear_cache'),
        type: 'post',
        success: function (result) {
            clearCacheButton.hide()
        },
        error: function (error) {
            logs.text(error.error);
        }
    });
});


$('#initCompare').click(function () {

    var startAt = $('#startAtCompare').val();
    $.ajax
    ({
        url: Routing.generate('_init_compare'),
        data: {"startAt": startAt},
        type: 'post',
        success: function (result) {
            pokemons = result.data.pokemons;
            learnMethod = result.data.learnMethod;
            generation = result.data.generations;
            $('#start-div').hide();
            $('#next-compare').show();
            pokemonIndex = 0;
            generationIndex = 0;
            maxPokemon = pokemons.length;
            maxGeneration = generation.length;
        },
        error: function (error) {
            logs.text(error.error);
        }
    });
});

nextButton.click(function () {

    $('#next-compare').hide();
    $('#upload-compare').hide();

    processDiff();
});

function processDiff() {

    $('#generated-moves').empty();
    $('#compare-div').empty();
    uploadButton.hide();
    $.ajax
    ({
        url: Routing.generate('_next_compare'),
        data: {
            "pokemon": pokemons[pokemonIndex],
            "generation": generation[generationIndex],
            "learnMethod": learnMethod
        },
        type: 'post',
        success: function (result) {
            if(!result.success) {
                debugger;
                logs.text(result.error);
                return;
            }
            if (generationIndex === maxGeneration - 1) {
                generationIndex = 0;
                pokemonIndex++;
            } else {
                generationIndex++;
            }

            if (!result.data.available || !result.data.diff) {
                logs.text(result.data.text);
                processDiff();

                return;
            }

            if(result.data.differentOrder) {
                title = result.data.page;
                section = result.data.section;
                wikiText = result.data.wikitext;
                uploadButton.click();
                logs.text(result.data.text + ' mais seulement dans le mauvais ordre, auto-uploading ...');
            } else {
                title = result.data.page;
                section = result.data.section;
                wikiText = result.data.wikitext;
                let diffString = result.data.diffString;
                let diff2htmlUi = new Diff2HtmlUI(compareDiv, diffString, configuration);
                try {
                    diff2htmlUi.draw();
                } catch (error) {
                }
                $('#generated-moves').html(result.data.generated)
                logs.text(result.data.text);
                uploadButton.show();
            }
        },
        error: function (result) {
            debugger;
            logs.text(result.responseJSON.error);
        },
    });
}

uploadButton.click(function () {
    nextButton.hide();
    uploadButton.hide();
    $.ajax
    ({
        url: Routing.generate('_upload_compare'),
        data: {
            "title": title,
            "section": section,
            "wikitext": wikiText
        },
        type: 'post',
        success: function (result) {
            if(!result.success) {
                logs.text(result.error)
                return;
            }
            logs.text(result.message);
            section = undefined;
            title = undefined;
            wikiText = undefined;
            processDiff();
        },
        error: function (result) {
            logs.text(result.responseText);
        },
        complete: function (result, status) {
        }
    });
});