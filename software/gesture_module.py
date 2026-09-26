import cv2
from cvzone.HandTrackingModule import HandDetector
import requests
import time

# IP ESP32 Kamu
ESP32_IP = "http://10.70.70.92"

# Inisialisasi Kamera & Detector
cap = cv2.VideoCapture(0)
detector = HandDetector(detectionCon=0.8, maxHands=1)

# Status Lampu & Kipas
lampu_status = False
kipas_status = False
last_action_time = 0
cooldown = 2  # Cooldown 2 detik

def send_request(endpoint):
    try:
        requests.get(f"{ESP32_IP}{endpoint}", timeout=1)
        print(f"--> Trigger ESP32: {endpoint}")
    except Exception as e:
        print(f"Gagal kirim sinyal ke ESP32: {e}")

print("=== JAROET AI: GESTURE CONTROL ACTIVE (CVZONE) ===")
print("Tunjukkan 1 Jari -> Toggle Lampu")
print("Tunjukkan 2 Jari -> Toggle Kipas")

while True:
    success, img = cap.read()
    if not success:
        break

    img = cv2.flip(img, 1)
    hands, img = detector.findHands(img)  # Deteksi tangan & gambar skeleton

    finger_count = 0

    if hands:
        hand1 = hands[0]
        fingers = detector.fingersUp(hand1)  # Mengembalikan list [IbuJari, Telunjuk, Tengah, Manis, Kelingking]
        finger_count = fingers.count(1)

        current_time = time.time()
        if current_time - last_action_time > cooldown:
            if finger_count == 1:
                lampu_status = not lampu_status
                action = "/lampu/on" if lampu_status else "/lampu/off"
                send_request(action)
                last_action_time = current_time
            elif finger_count == 2:
                kipas_status = not kipas_status
                action = "/kipas/on" if kipas_status else "/kipas/off"
                send_request(action)
                last_action_time = current_time

    # Tampilkan Teks Status
    cv2.putText(img, f"Jari Terdeteksi: {finger_count}", (20, 50),
                cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
    cv2.putText(img, f"Lampu: {'ON' if lampu_status else 'OFF'} | Kipas: {'ON' if kipas_status else 'OFF'}",
                (20, 90), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)

    cv2.imshow("JAROET AI - Gesture Recognition", img)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()