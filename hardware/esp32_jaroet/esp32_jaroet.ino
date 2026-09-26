#include <WiFi.h>
#include <WebServer.h>

// Ganti dengan nama dan password Wi-Fi kamu
const char* ssid = "LP Santri";
const char* password = "";

WebServer server(80);

// Pin Relay (sesuaikan dengan pin yang kamu pakai di ESP32)
const int RELAY_LAMPU = 23;
const int RELAY_KIPAS = 22;

void handleRoot() {
  server.send(200, "text/plain", "JAROET AI - ESP32 Server Active!");
}

void handleLampuOn() {
  digitalWrite(RELAY_LAMPU, LOW); // Relay ON (active LOW)
  server.send(200, "text/plain", "Lampu Menyala");
}

void handleLampuOff() {
  digitalWrite(RELAY_LAMPU, HIGH); // Relay OFF
  server.send(200, "text/plain", "Lampu Mati");
}

void handleKipasOn() {
  digitalWrite(RELAY_KIPAS, LOW);
  server.send(200, "text/plain", "Kipas Menyala");
}

void handleKipasOff() {
  digitalWrite(RELAY_KIPAS, HIGH);
  server.send(200, "text/plain", "Kipas Mati");
}

void setup() {
  Serial.begin(115200);
  
  pinMode(RELAY_LAMPU, OUTPUT);
  pinMode(RELAY_KIPAS, OUTPUT);
  // Kondisi awal mati
  digitalWrite(RELAY_LAMPU, HIGH);
  digitalWrite(RELAY_KIPAS, HIGH);

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.print("Connected! IP Address: ");
  Serial.println(WiFi.localIP());

  // Routing Endpoint HTTP
  server.on("/", handleRoot);
  server.on("/lampu/on", handleLampuOn);
  server.on("/lampu/off", handleLampuOff);
  server.on("/kipas/on", handleKipasOn);
  server.on("/kipas/off", handleKipasOff);

  server.begin();
  Serial.println("HTTP server started");
}

void loop() {
  server.handleClient();
}