<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAROET AI - Web Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center p-4 md:p-8">

    <header class="w-full max-w-4xl flex justify-between items-center mb-8 glass p-4 rounded-2xl">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-blue-600 rounded-xl text-white font-bold text-xl"><i class="fa-solid fa-microchip"></i></div>
            <div>
                <h1 class="text-xl font-bold tracking-wide">JAROET AI Web Controller</h1>
                <p class="text-xs text-slate-400">ESP32 Static IP: <span class="text-emerald-400 font-mono">10.70.70.35</span></p>
            </div>
        </div>
        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-semibold rounded-full flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span> Server Active
        </span>
    </header>

    <main class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- SAKLAR MANUAL -->
        <section class="md:col-span-1 flex flex-col gap-4">
            <h2 class="text-lg font-semibold text-slate-300 mb-1"><i class="fa-solid fa-sliders text-blue-400 mr-2"></i>Saklar Manual</h2>
            
            <div class="glass p-5 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="icon-lampu" class="p-3 bg-slate-700 text-slate-400 rounded-xl text-lg"><i class="fa-solid fa-lightbulb"></i></div>
                    <div>
                        <h3 class="font-semibold text-sm">Lampu Utama</h3>
                        <p id="label-lampu" class="text-xs text-slate-400">Status: OFF</p>
                    </div>
                </div>
                <button onclick="toggleDevice('lampu')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-xl transition">
                    TOGGLE
                </button>
            </div>

            <div class="glass p-5 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="icon-kipas" class="p-3 bg-slate-700 text-slate-400 rounded-xl text-lg"><i class="fa-solid fa-fan"></i></div>
                    <div>
                        <h3 class="font-semibold text-sm">Kipas Angin</h3>
                        <p id="label-kipas" class="text-xs text-slate-400">Status: OFF</p>
                    </div>
                </div>
                <button onclick="toggleDevice('kipas')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-xl transition">
                    TOGGLE
                </button>
            </div>
        </section>

        <!-- CHATBOT INTERAKTIF -->
        <section class="md:col-span-2 glass rounded-2xl flex flex-col h-[480px]">
            <div class="p-4 border-b border-slate-700/50 flex items-center justify-between">
                <h2 class="text-md font-semibold text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-robot text-blue-400"></i> JAROET AI Assistant
                </h2>
                <span class="text-xs text-slate-400">NLP Powered</span>
            </div>

            <div id="chat-box" class="flex-1 p-4 overflow-y-auto flex flex-col gap-3">
                <div class="bg-slate-800 text-slate-300 p-3 rounded-2xl rounded-tl-none max-w-[80%] text-sm border border-slate-700/50">
                    Halo! Aku assistant JAROET AI. Kamu bisa ketik kalimat bebas seperti <i>"aku pengen kipas di hidupin"</i> atau <i>"matikan lampu dong"</i>.
                </div>
            </div>

            <form onsubmit="sendMessage(event)" class="p-3 border-t border-slate-700/50 flex gap-2">
                <input type="text" id="user-input" placeholder="Tulis perintah..." autocomplete="off"
                    class="flex-1 bg-slate-900 border border-slate-700 text-white text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </section>

    </main>

    <script>
        let states = { lampu: false, kipas: false };

        async function toggleDevice(device) {
            states[device] = !states[device];
            const action = states[device] ? 'on' : 'off';
            
            updateUI(device, states[device]);

            try {
                await fetch(`api.php?device=${device}&action=${action}`);
            } catch (e) {
                console.error("Gagal koneksi API:", e);
            }
        }

        function updateUI(device, isOn) {
            const label = document.getElementById(`label-${device}`);
            const icon = document.getElementById(`icon-${device}`);
            
            if (isOn) {
                label.innerText = "Status: ON";
                label.className = "text-xs text-emerald-400 font-semibold";
                icon.className = "p-3 bg-emerald-500/20 text-emerald-400 rounded-xl text-lg animate-pulse";
            } else {
                label.innerText = "Status: OFF";
                label.className = "text-xs text-slate-400";
                icon.className = "p-3 bg-slate-700 text-slate-400 rounded-xl text-lg";
            }
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('user-input');
            const message = input.value.trim();
            if (!message) return;

            appendMessage('user', message);
            input.value = '';

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });
                const data = await res.json();
                appendMessage('bot', data.reply);
                
                if (data.deviceStatus) {
                    states[data.deviceStatus.device] = data.deviceStatus.state;
                    updateUI(data.deviceStatus.device, data.deviceStatus.state);
                }
            } catch (err) {
                appendMessage('bot', '⚠️ Terjadi kesalahan jaringan ke server API PHP.');
            }
        }

        function appendMessage(sender, text) {
            const box = document.getElementById('chat-box');
            const div = document.createElement('div');
            
            if (sender === 'user') {
                div.className = "bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none max-w-[80%] text-sm ml-auto shadow-md";
            } else {
                div.className = "bg-slate-800 text-slate-300 p-3 rounded-2xl rounded-tl-none max-w-[80%] text-sm border border-slate-700/50 shadow-md";
            }
            
            div.innerText = text;
            box.appendChild(div);
            box.scrollTop = box.scrollHeight;
        }
    </script>

</body>
</html>