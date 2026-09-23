\# 🤖 JAROET AI (Smart IoT \& AI Assistant)



!\[Python](https://img.shields.io/badge/Python-3.10%2B-blue?style=for-the-badge\&logo=python\&logoColor=white)

!\[ESP32](https://img.shields.io/badge/Hardware-ESP32-red?style=for-the-badge\&logo=espressif\&logoColor=white)

!\[OpenCV](https://img.shields.io/badge/AI-Computer\_Vision-green?style=for-the-badge\&logo=opencv\&logoColor=white)

!\[Telegram](https://img.shields.io/badge/Control-Telegram\_Bot-26A5E4?style=for-the-badge\&logo=telegram\&logoColor=white)



\*\*JAROET AI\*\* adalah sistem asisten \*smart room\* berbasis \*\*AI, Computer Vision, dan IoT (ESP32)\*\*. Proyek ini memadukan interaksi multi-mode: mulai dari gestur jari/tangan, perintah suara manusia, hingga \*remote control\* via Telegram Bot secara \*real-time\*.



\---



\## ✨ Key Features (Fitur Utama)



\### 1. 🖐️ Gesture Recognition (Control via Jari)

\- \*\*1 Jari (Telunjuk):\*\* Nyalakan / Matikan Lampu Utama.

\- \*\*2 Jari:\*\* Kontrol Perangkat Tambahan / Mode Standby.

\- Menganalisis gerakan tangan secara \*real-time\* menggunakan \*\*MediaPipe / OpenCV\*\* melalui kamera.



\### 2. 🎙️ Voice Command (Control via Suara)

\- Memproses perintah suara secara otomatis (\*Speech-to-Text\*).

\- \*Contoh Perintah:\* `"Nyalakan kipas"`, `"Matikan lampu"`.

\- Terhubung dengan AI untuk memahami konteks dan mengeksekusi sinyal ke hardware.



\### 3. 💬 Telegram Bot \& Custom Dashboard

\- \*\*Chat Control:\*\* Mengirim pesan teks langsung ke AI Bot (Contoh: \*"Tolong nyalakan lampu"\*).

\- \*\*Interactive Inline Keyboard:\*\* Saklar digital (\*Button Switch\*) langsung di dalam aplikasi Telegram untuk mematikan/menyalakan alat secara fleksibel dari mana saja.



\---



\## 🛠️ System Architecture (Cara Kerja Sistem)



```text

&#x20;      ┌──────────────────────────────────────────────┐

&#x20;      │             INPUT PERINTAH USER              │

&#x20;      ├──────────────────┬────────────┬──────────────┤

&#x20;      │ Gesture (Kamera) │ Voice (Mic)│ Telegram Bot │

&#x20;      └────────┬─────────┴─────┬──────┴──────┬───────┘

&#x20;               │               │             │

&#x20;               ▼               ▼             ▼

&#x20;      ┌──────────────────────────────────────────────┐

&#x20;      │              PYTHON AI ENGINE                │

&#x20;      │      (Computer Vision \& NLP Processing)      │

&#x20;      └──────────────────────┬───────────────────────┘

&#x20;                             │

&#x20;                   (HTTP / Wi-Fi Signal)

&#x20;                             │

&#x20;                             ▼

&#x20;      ┌──────────────────────────────────────────────┐

&#x20;      │                ESP32 BOARD                   │

&#x20;      └──────────────────────┬───────────────────────┘

&#x20;                             │

&#x20;                      (GPIO Output)

&#x20;                             │

&#x20;                             ▼

&#x20;      ┌──────────────────────────────────────────────┐

&#x20;      │                 RELAY MODULE                 │

&#x20;      │           \[ Saklar Listrik Fisik ]           │

&#x20;      └──────────────┬────────────────┬──────────────┘

&#x20;                     │                │

&#x20;                     ▼                ▼

&#x20;               💡 Lampu Utama   🌀 Kipas Angin

