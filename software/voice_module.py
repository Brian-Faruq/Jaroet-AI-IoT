import time
import requests
import sounddevice as sd
import scipy.io.wavfile as wav
import speech_recognition as sr
import os

# IP ESP32 Kamu
ESP32_IP = "http://10.70.70.92"
TEMP_AUDIO_FILE = "temp_voice.wav"
SAMPLE_RATE = 44100
DURATION = 4  # Durasi merekam dalam detik per sesi

def send_request(endpoint):
    try:
        response = requests.get(f"{ESP32_IP}{endpoint}", timeout=2)
        print(f"--> ESP32 Response: {response.text}")
    except Exception as e:
        print(f"Gagal mengirim perintah ke ESP32: {e}")

def listen_command():
    print(f"\n[JAROET AI] Mendengarkan... Bicara selama {DURATION} detik!")
    
    # Merekam audio via sounddevice (Tanpa PyAudio)
    recording = sd.rec(int(DURATION * SAMPLE_RATE), samplerate=SAMPLE_RATE, channels=1, dtype='int16')
    sd.wait()
    
    # Simpan sementara ke file wav
    wav.write(TEMP_AUDIO_FILE, SAMPLE_RATE, recording)
    
    recognizer = sr.Recognizer()
    command = ""
    
    try:
        with sr.AudioFile(TEMP_AUDIO_FILE) as source:
            audio_data = recognizer.record(source)
            command = recognizer.recognize_google(audio_data, language="id-ID").lower()
            print(f"[Kamu Bicara]: '{command}'")
    except sr.UnknownValueError:
        print("[JAROET AI]: Suara tidak terdengar jelas.")
    except sr.RequestError:
        print("[JAROET AI]: Gagal terhubung ke layanan pengenal suara.")
    finally:
        if os.path.exists(TEMP_AUDIO_FILE):
            os.remove(TEMP_AUDIO_FILE)
            
    return command

print("=== JAROET AI: VOICE CONTROL ACTIVE (SOUNDDEVICE) ===")
print("Contoh Perintah Suara:")
print(" - 'nyalakan lampu' / 'matikan lampu'")
print(" - 'nyalakan kipas' / 'matikan kipas'")
print(" - 'keluar' (untuk menghentikan program)")

while True:
    command = listen_command()

    if "nyalakan lampu" in command or "hidupkan lampu" in command:
        send_request("/lampu/on")
    elif "matikan lampu" in command:
        send_request("/lampu/off")
    elif "nyalakan kipas" in command or "hidupkan kipas" in command:
        send_request("/kipas/on")
    elif "matikan kipas" in command:
        send_request("/kipas/off")
    elif "keluar" in command or "stop" in command:
        print("[JAROET AI]: Voice module dimatikan.")
        break
    
    time.sleep(0.5)