(function ($) {
    'use strict';

    // Plugin default options
    var defaultOptions = {
    };

    // If the plugin is a button
    function buildButtonDef(trumbowyg) {
        return {
            fn: function () {
                // Créez une fenêtre modale de Trumbowyg pour saisir les détails du StrawPoll
                var modalContent = '<label>Question : <input id="pollQuestion" type="text"></label>';
                modalContent += '<label>Options :';
                modalContent += '<input class="option-input" type="text">';
                modalContent += '<input class="option-input" type="text">';
                modalContent += '<button id="addOption">Ajouter une option</button>';
                modalContent += '</label>';

                modalContent += '<button id="customConfirm">Créer ce sondage</button>';
                modalContent += '<button id="customCancel">Annuler</button>';

                trumbowyg.openModal('Saisir les détails du StrawPoll', modalContent, function () {
                    var pollQuestion = $('#pollQuestion').val();
                    if (!pollQuestion) return;

                    // Récupérez les options
                    var pollOptions = [];
                    $('.option-input').each(function () {
                        var optionText = $(this).val();
                        if (optionText.trim() !== '') {
                            pollOptions.push(optionText);
                        }
                    });

                    // Gérer la création du StrawPoll
                    var pollData = {
                        title: pollQuestion,
                        poll_options: pollOptions,
                        type: 'multiple_choice', // Vous pouvez modifier le type ici
                        // Ajoutez d'autres champs de données en fonction de vos besoins
                    };

                    // Paramètres pour la requête POST
                    var url = 'https://api.strawpoll.com/v3/polls';
                    var apiKey = 'e2357040-71cb-11ee-ba08-a87f99aa68de'; // Remplacez par votre clé API

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-API-Key': apiKey
                        },
                        body: JSON.stringify(pollData)
                    })
                    .then(function (response) {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Erreur lors de la création du StrawPoll');
                    })
                    .then(function (data) {
                        // Insérer le StrawPoll dans l'éditeur
                        var strawPollHtml = '<iframe src="' + data.url + '"></iframe>';
                        trumbowyg.execCmd('insertHTML', strawPollHtml);
                    })
                    .catch(function (error) {
                        console.error('Erreur lors de la création du StrawPoll : ' + error.message);
                    });

                    // Fermez la fenêtre modale
                    trumbowyg.closeModal();
                });

                // Gérer l'ajout de champs d'option
                $('#addOption').on('click', function () {
                    var newOptionInput = '<input class="option-input" type="text">';
                    $('.option-input:last').after(newOptionInput);
                });

                // Gérer l'événement "Annuler"
                $('#customCancel').on('click', function () {
                    // Fermez la fenêtre modale
                    trumbowyg.closeModal();
                });

                // Gérer l'événement "Confirmer"
                $('#customConfirm').on('click', function () {
                    console.log('Créer un StrawPoll');
                    var pollQuestion = $('#pollQuestion').val();
                    if (!pollQuestion) return;

                    // Récupérez les options
                    var pollOptions = [];
                    $('.option-input').each(function (index) {
                        var optionText = $(this).val();
                        if (optionText.trim() !== '') {
                            var pollOption = {
                                id: 'option-' + index, // Vous pouvez générer un ID unique ici
                                type: 'text',
                                position: index,
                                vote_count: 0,
                                max_votes: 0,
                                description: 'This is a description text',
                                is_write_in: false,
                                value: optionText
                            };
                            pollOptions.push(pollOption);
                        }
                    });
                    console.log(pollOptions);

                    // Gérer la création du StrawPoll
                    var pollData = {
                        title: pollQuestion,
                        poll_options: pollOptions,
                        type: 'multiple_choice', // Vous pouvez modifier le type ici
                        // Ajoutez d'autres champs de données en fonction de vos besoins
                    };

                    // Paramètres pour la requête POST
                    var url = 'https://api.strawpoll.com/v3/polls';
                    var apiKey = 'e2357040-71cb-11ee-ba08-a87f99aa68de'; // Remplacez par votre clé API

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-API-Key': apiKey
                        },
                        body: JSON.stringify(pollData)
                    })
                    .then(function (response) {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Erreur lors de la création du StrawPoll');
                    })
                    .then(function (data) {
                        console.log(data);
                        var strawPollHtml = '<iframe src="' + data.embed_url + '"></iframe>';
                        trumbowyg.execCmd('insertHTML', strawPollHtml);
                        trumbowyg.closeModal();
                    })
                    .catch(function (error) {
                        console.error('Erreur lors de la création du StrawPoll : ' + error.message);
                    });
                });

            }
        };
    }

    // If the plugin is a button
    function buildButtonIcon() {
        if ($("#trumbowyg-myplugin").length > 0) {
            return;
        }

        const iconWrap = $(document.createElementNS("http://www.w3.org/2000/svg", "svg"));
        iconWrap.addClass("trumbowyg-icons");

        // Icône personnalisée pour le bouton "StrawPoll"
        iconWrap.html(`
      <symbol id="trumbowyg-myplugin" viewBox="0 0 24 24">
        <path d="M21 8v12.993A1 1 0 0 1 20.007 22H3.993A.993.993 0 0 1 3 21.008V2.992C3 2.455 3.449 2 4.002 2h10.995L21 8zm-2 1h-5V4H5v16h14V9zM8 7h3v2H8V7zm0 4h8v2H8v-2zm0 4h8v2H8v-2z"/>
      </symbol>
    `).appendTo(document.body);
    }

    $.extend(true, $.trumbowyg, {
        // Add some translations
        langs: {
            en: {
                myplugin: 'My plugin'
            }
        },
        // Register plugin in Trumbowyg
        plugins: {
            myplugin: {
                // Code called by Trumbowyg core to register the plugin
                init: function (trumbowyg) {
                    // Fill current Trumbowyg instance with the plugin default options
                    trumbowyg.o.plugins.myplugin = $.extend(true, {},
                        defaultOptions,
                        trumbowyg.o.plugins.myplugin || {}
                    );

                    // If the plugin is a button
                    buildButtonIcon();
                    trumbowyg.addBtnDef('strawpoll', buildButtonDef(trumbowyg));
                }
            }
        }
    });
})(jQuery);
