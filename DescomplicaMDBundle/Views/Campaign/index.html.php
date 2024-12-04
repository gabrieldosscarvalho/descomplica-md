<?php

echo $view['translator']->trans('Hello from DescomplicaMD plugin!');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DescomplicaMD Plugin</title>
    <style>
        .descomplicamd .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .descomplicamd h1,
        .descomplicamd h2 {
            margin-bottom: 20px;
        }

        .descomplicamd p {
            margin-bottom: 20px;
        }

        .descomplicamd .form-group {
            margin-bottom: 15px;
        }

        .descomplicamd .btn {
            margin-top: 10px;
        }

        .descomplicamd ul {
            list-style-type: none;
            padding: 0;
        }

        .descomplicamd li {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .descomplicamd .campaign-action-form {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container descomplicamd">
        <h1>DescomplicaMD Plugin</h1>
        <p>Bem-vindo ao plugin DescomplicaMD para Mautic.</p>

        <!-- <h2>Trocar Token da API</h2>
        <form method="post" action="<?php echo $view['router']->path('plugin_descomplicamd_update_token'); ?>">
            <div class="form-group">
                <label for="api_key">Novo Token da API:</label>
                <input type="text" id="api_key" name="api_key" class="form-control" value="<?php echo htmlspecialchars($apiKey); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Token</button>
        </form> -->

        <h2>Campanhas</h2>
        <ul>
            <?php foreach ($campaigns as $campaign): ?>
                <li>
                    <?php echo $campaign['name']; ?>
                    <form class="campaign-action-form" data-campaign-id="<?php echo $campaign['id']; ?>">
                        <div class="form-group">
                            <label for="action">Escolha a ação para a campanha:</label>
                            <select id="action" name="action" class="form-control" required>
                                <option value="analyze">Analisar</option>
                                <option value="predict">Prever Desempenho</option>
                                <option value="optimize">Otimizar</option>
                                <option value="suggest">Pedir Sugestões</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Executar</button>
                    </form>
                    <div class="campaign-result" id="campaign-result-<?php echo $campaign['id']; ?>"></div>
                    <div class="loading" id="loading-<?php echo $campaign['id']; ?>" style="display: none;">Carregando...</div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- <h2>Chatbot</h2>
        <div id="chat-container">
            <div id="chat-messages"></div>
            <div id="active-campaign"></div>
            <form id="chat-form">
                <div class="form-group">
                    <label for="chat-action">Escolha a ação:</label>
                    <select id="chat-action" name="action" class="form-control" required>
                        <option value="analyze">Analisar</option>
                        <option value="predict">Prever Desempenho</option>
                        <option value="optimize">Otimizar</option>
                        <option value="suggest">Pedir Sugestões</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="chat-message">Sua mensagem (opcional):</label>
                    <input type="text" id="chat-message" name="message" class="form-control">
                    <input type="hidden" id="campaign-id" name="campaignId" value="">
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div> -->
    </div>

    <script>
        document.querySelectorAll('.campaign-action-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const campaignId = this.dataset.campaignId;
                const action = this.querySelector('select[name="action"]').value;
                const resultContainer = document.getElementById(`campaign-result-${campaignId}`);
                const loadingIndicator = document.getElementById(`loading-${campaignId}`);

                resultContainer.innerHTML = '';
                loadingIndicator.style.display = 'block';

                const url = '<?php echo $view['router']->path('plugin_descomplicamd_campaign_action', ['campaignId' => 'CAMPAIGN_ID']); ?>'.replace('CAMPAIGN_ID', campaignId);

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: action
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = 'none';
                        if (data.error) {
                            resultContainer.innerHTML = `<div><strong>Erro:</strong> ${data.error}</div>`;
                        } else {
                            const messageContent = data.choices[0].message.content.replace(/\n/g, '<br>');
                            resultContainer.innerHTML = `<div><strong>Resultado:</strong> ${messageContent}</div>`;
                            // startChat(campaignId, messageContent, '<?php echo $campaign['name']; ?>');
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = 'none';
                        resultContainer.innerHTML = `<div><strong>Erro:</strong> ${error.message}</div>`;
                    });
            });
        });

        function startChat(campaignId, initialMessage, campaignName) {
            document.getElementById('campaign-id').value = campaignId;
            document.getElementById('active-campaign').innerHTML = `<div><strong>Campanha Ativa:</strong> ${campaignName}</div>`;
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = `<div><strong>Chatbot:</strong> ${initialMessage.replace(/\n/g, '<br>')}</div>`;
        }

        // document.getElementById('chat-form').addEventListener('submit', function(event) {
        //     event.preventDefault();
        //     const messageInput = document.getElementById('chat-message');
        //     const message = messageInput.value;
        //     const action = document.getElementById('chat-action').value;
        //     const campaignId = document.getElementById('campaign-id').value;
        //     messageInput.value = '';

        //     const chatMessages = document.getElementById('chat-messages');
        //     if (message) {
        //         chatMessages.innerHTML += `<div><strong>Você:</strong> ${message}</div>`;
        //     }

        //     fetch('<?php echo $view['router']->path('plugin_descomplicamd_chat'); ?>', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //             body: JSON.stringify({
        //                 action: action,
        //                 message: message,
        //                 campaignId: campaignId
        //             }),
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.error) {
        //                 chatMessages.innerHTML += `<div><strong>Erro:</strong> ${data.error}</div>`;
        //             } else {
        //                 chatMessages.innerHTML += `<div><strong>Chatbot:</strong> ${data.choices[0].message.content.replace(/\n/g, '<br>')}</div>`;
        //             }
        //         })
        //         .catch(error => {
        //             chatMessages.innerHTML += `<div><strong>Erro:</strong> ${error.message}</div>`;
        //         });
        // });
    </script>
</body>

</html>