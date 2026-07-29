#include <SPI.h>
#include <MFRC522.h>

// --- PINLARNI SOZLASh ---
// RFID-RC522 modul pinlari
#define SS_PIN 10
#define RST_PIN 9

// Qulf/Relay, LED va Buzzer pinlari
#define RELAY_PIN 5
#define GREEN_LED 6
#define RED_LED 7
#define BUZZER_PIN 8

// MFRC522 obyektini yaratish
MFRC522 mfrc522(SS_PIN, RST_PIN);

// --- RUXSAT ETILGAN KARTALAR (UID) RO'YXATI ---
// 2 o'lchamli massiv. Kelajakda yangi karta qo'shmoqchi bo'lsangiz, 
// shu yerga vergul bilan yangi {0x.., 0x.., 0x.., 0x..} ni qo'shasiz.
const byte allowedUIDs[][4] = {
  {0x97, 0xBE, 0x4F, 0x06},
  {0x27, 0x74, 0x0B, 0x07},
  {0xE9, 0x55, 0x73, 0x06}
};

// Massivdagi kartalar sonini avtomatik hisoblash
const int totalAllowedCards = sizeof(allowedUIDs) / sizeof(allowedUIDs[0]);

void setup() {
  // Serial monitorni ishga tushirish (9600 baud)
  Serial.begin(9600);
  
  // SPI interfeysini ishga tushirish
  SPI.begin();
  
  // RFID modulini ishga tushirish
  mfrc522.PCD_Init();
  
  // Pinlarni chiqish (OUTPUT) rejimiga o'tkazish
  pinMode(RELAY_PIN, OUTPUT);
  pinMode(GREEN_LED, OUTPUT);
  pinMode(RED_LED, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  
  // Boshlang'ich holatda hamma narsa o'chirilgan bo'lishi kerak
  digitalWrite(RELAY_PIN, LOW);
  digitalWrite(GREEN_LED, LOW);
  digitalWrite(RED_LED, LOW);
  digitalWrite(BUZZER_PIN, LOW);

  Serial.println("Tizim ishga tushdi. Karta kuting...");
}

void loop() {
  // Yangi karta yaqinlashtirilganini tekshirish
  if (!mfrc522.PICC_IsNewCardPresent()) {
    return; // Karta yo'q bo'lsa, loopni qayta boshlash
  }
  
  // Kartadan ma'lumot (UID) ni o'qish
  if (!mfrc522.PICC_ReadCardSerial()) {
    return; // O'qishda xatolik bo'lsa, qayta boshlash
  }

  // O'qilgan kartani tekshirish
  if (checkUID(mfrc522.uid.uidByte, mfrc522.uid.size)) {
    // Agar karta to'g'ri bo'lsa
    grantAccess();
  } else {
    // Agar karta noto'g'ri bo'lsa
    denyAccess();
  }

  // Kartani o'qishni to'xtatish (keyingi o'qishgacha kutish uchun)
  mfrc522.PICC_HaltA();
}

/**
 * O'qilgan UID ni ruxsat berilgan kartalar ro'yxati bilan solishtiruvchi funksiya
 */
bool checkUID(byte *readUID, byte uidSize) {
  // Odatda MIFARE Classic kartalari UID si 4 bayt bo'ladi
  if (uidSize != 4) return false;

  // Har bir ruxsat berilgan karta bilan solishtirib chiqamiz
  for (int i = 0; i < totalAllowedCards; i++) {
    bool match = true;
    for (int j = 0; j < 4; j++) {
      if (readUID[j] != allowedUIDs[i][j]) {
        match = false;
        break; // Bitta bayt xato bo'lsa, keyingisini tekshirish shart emas
      }
    }
    // Agar hamma 4 ta bayt mos kelsa, ruxsat bor
    if (match) {
      return true;
    }
  }
  // Hech biriga mos kelmasa, ruxsat yo'q
  return false;
}

/**
 * Ruxsat berilganda bajariladigan harakatlar
 */
void grantAccess() {
  Serial.println("✅ Ruxsat berildi!");
  
  // Qulfni ochish (Relayni yoqish) va yashil LEDni yoqish
  digitalWrite(RELAY_PIN, HIGH);
  digitalWrite(GREEN_LED, HIGH);
  
  // 3 soniya kutish
  delay(3000);
  
  // Qulfni yopish va LEDni o'chirish
  digitalWrite(RELAY_PIN, LOW);
  digitalWrite(GREEN_LED, LOW);
}

/**
 * Ruxsat etilmaganda bajariladigan harakatlar
 */
void denyAccess() {
  Serial.println("❌ Ruxsat yo'q!");
  
  // Qizil LEDni va Buzzerni yoqish
  digitalWrite(RED_LED, HIGH);
  digitalWrite(BUZZER_PIN, HIGH);
  
  // 1 soniya kutish (Buzzer ovozi va LED yonib turishi uchun)
  delay(1000);
  
  // O'chirish
  digitalWrite(RED_LED, LOW);
  digitalWrite(BUZZER_PIN, LOW);
}
