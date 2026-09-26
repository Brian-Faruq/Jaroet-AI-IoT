import time
import requests

# Masukkan IP Address ESP32 yang kamu dapat dari Serial Monitor tadi
ESP32_IP = "http://10.70.70.92"  


def send_command(endpoint):
    url = f"{ESP32_IP}{endpoint}"
    try:
        response = requests.get(url, timeout=3)
        print(f"--> Response ESP32: {response.text}")
    except Exception as e:
        print(f"Gagal koneksi ke ESP32: {e}")


print("=== TES KONTROL HARDWARE VIA PYTHON ===")

# Tes Lampu
print("Menyalakan Lampu...")
send_command("/lampu/on")
time.sleep(2)

print("Mematikan Lampu...")
send_command("/lampu/off")
time.sleep(2)

# Tes Kipas
print("Menyalakan Kipas...")
send_command("/kipas/on")
time.sleep(2)

print("Mematikan Kipas...")
send_command("/kipas/off")

print("=== TES SELESAI ===")