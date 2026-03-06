# Barbershop Projekt

Dieses Repository enthält die Projektunterlagen und Artefakte für das Barbershop-Projekt (IHK/Projektarbeit).

## Inhalt

- **Datenbank**
  - `Barbershop DB Schema.sql` – SQL-Schema (Tabellen/Beziehungen)
- **Projektmanagement / Planung**
  - `PSP_BarberShop.xlsx` – Projektstrukturplan
  - `Planungsdokumente/zeitplan.pdf` – Zeitplan
  - `Planungsdokumente/psp.pdf` – PSP als PDF
- **Dokumentation**
  - `Projektdokumentation.docx` / `Projektdokumentation_BarbarShop.docx` – Projektdoku (Arbeitsstand)
  - `IHK Projektdokumentation_BarberShop_Kronas.odg` – Diagramme/Grafiken
  - `IHK Projektdokumentation_Vorlage (mit_Anweisungen).pdf` – Vorlage/Referenz
- **Weitere Unterlagen**
  - `Projektantrag_BarberShop.pdf` – Projektantrag
  - `PSP.pdf` – Export/Report
  - `zeitplan_feini.ods` – Zeitplan (Calc/ODS)
  - `Projektwochen (1).docx` – Wochen-/Statusdokument

> Hinweis: Manche Dateien sind Arbeitsstände und können sich noch ändern.

## Voraussetzungen

- **MariaDB / MySQL**
- **SQL-Client** (z.B. HeidiSQL, phpMyAdmin, DBeaver)

## Datenbank einrichten

1. Datenbank in MariaDB/MySQL anlegen (z.B. `barbershop`).
2. Datei `Barbershop DB Schema.sql` importieren.
3. Danach können ggf. Seed-/Testdaten ergänzt werden (falls vorhanden).

## Arbeiten mit Git (kurz)

Änderungen committen und hochladen:

```bash
git add .
git commit -m "Update README / Dokumente"
git push