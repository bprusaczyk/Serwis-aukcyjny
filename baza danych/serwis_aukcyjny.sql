-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 09 Sty 2019, 13:42
-- Wersja serwera: 10.1.36-MariaDB
-- Wersja PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `serwis_aukcyjny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `idKategorii` int(11) NOT NULL,
  `nazwa` varchar(18) COLLATE utf32_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_polish_ci;

--
-- Zrzut danych tabeli `kategorie`
--

INSERT INTO `kategorie` (`idKategorii`, `nazwa`) VALUES
(1, 'Artykuły spożywcze'),
(2, 'Biżuteria'),
(3, 'Dla dzieci'),
(4, 'Dom i ogród'),
(5, 'Elektronika'),
(6, 'Filmy'),
(7, 'Firma'),
(8, 'Kolekcje i sztuka'),
(9, 'Książki'),
(10, 'Motoryzacja'),
(11, 'Muzyka'),
(12, 'Odzież i obuwie'),
(13, 'Sport i wypoczynek'),
(14, 'Uroda i zdrowie'),
(15, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyki`
--

CREATE TABLE `koszyki` (
  `idKoszyka` int(11) NOT NULL,
  `idUzytkownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kwoty`
--

CREATE TABLE `kwoty` (
  `idKwoty` int(11) NOT NULL,
  `idOferty` int(11) NOT NULL,
  `kwota` decimal(10,2) DEFAULT NULL,
  `idUzytkownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `idOceny` int(11) NOT NULL,
  `idOferty` int(11) NOT NULL,
  `ocena` int(2) NOT NULL,
  `komentarz` text COLLATE utf8mb4_unicode_ci,
  `idZamowienia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oferty`
--

CREATE TABLE `oferty` (
  `idOferty` int(11) NOT NULL,
  `idUzytkownika` int(11) DEFAULT NULL,
  `nazwa` text COLLATE utf32_polish_ci NOT NULL,
  `dataZakonczenia` datetime DEFAULT NULL,
  `idKategorii` int(11) NOT NULL,
  `opisOferty` text COLLATE utf32_polish_ci NOT NULL,
  `stan` text COLLATE utf32_polish_ci,
  `cenaKurierZGory` decimal(10,2) DEFAULT NULL,
  `czasKurierZGory` int(11) DEFAULT NULL,
  `cenaKurierPrzyOdbiorze` decimal(10,2) DEFAULT NULL,
  `czasKurierPrzyOdbiorze` int(11) DEFAULT NULL,
  `czasOdbiorOsobisty` int(11) DEFAULT NULL,
  `opisDostawy` text COLLATE utf32_polish_ci,
  `czasNaZwrot` int(11) NOT NULL,
  `adresDoZwrotu` text COLLATE utf32_polish_ci NOT NULL,
  `kosztyZwrotu` varchar(11) COLLATE utf32_polish_ci NOT NULL,
  `opisZwrotow` text COLLATE utf32_polish_ci,
  `okresGwarancji` int(11) DEFAULT NULL,
  `opisGwarancji` text COLLATE utf32_polish_ci,
  `wybranySposobDostawy` varchar(31) COLLATE utf32_polish_ci DEFAULT NULL,
  `typOferty` varchar(9) COLLATE utf32_polish_ci NOT NULL,
  `cena` decimal(10,2) DEFAULT NULL,
  `liczbaSztuk` int(11) DEFAULT NULL,
  `przeczytana` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `idUzytkownika` int(8) NOT NULL,
  `login` text COLLATE utf32_polish_ci NOT NULL,
  `haslo` text COLLATE utf32_polish_ci NOT NULL,
  `email` text COLLATE utf32_polish_ci NOT NULL,
  `imie` text COLLATE utf32_polish_ci,
  `nazwisko` text COLLATE utf32_polish_ci,
  `kraj` text COLLATE utf32_polish_ci,
  `miejscowosc` text COLLATE utf32_polish_ci,
  `ulica` text COLLATE utf32_polish_ci,
  `numerBudynku` text COLLATE utf32_polish_ci,
  `numerMieszkania` text COLLATE utf32_polish_ci,
  `numerTelefonu` text COLLATE utf32_polish_ci,
  `emailKontaktowy` text COLLATE utf32_polish_ci,
  `numerKontaBankowego` text COLLATE utf32_polish_ci,
  `kodPocztowy` text COLLATE utf32_polish_ci,
  `ostatniaProbaLogowania` datetime DEFAULT NULL,
  `liczbaNieudanychProbLogowania` int(2) NOT NULL DEFAULT '0',
  `odblokowanieKonta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `idZamowienia` int(11) NOT NULL,
  `idKoszyka` int(11) NOT NULL,
  `idOferty` int(11) NOT NULL,
  `liczbaSztuk` int(11) NOT NULL,
  `wybranySposobDostawy` varchar(31) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `przeczytane` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE `zdjecia` (
  `idZdjecia` int(11) NOT NULL,
  `idOferty` int(11) NOT NULL,
  `sciezka` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`idKategorii`);

--
-- Indeksy dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  ADD PRIMARY KEY (`idKoszyka`),
  ADD KEY `idUzytkownika` (`idUzytkownika`);

--
-- Indeksy dla tabeli `kwoty`
--
ALTER TABLE `kwoty`
  ADD PRIMARY KEY (`idKwoty`),
  ADD KEY `idOferty` (`idOferty`),
  ADD KEY `idUzytkownika` (`idUzytkownika`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`idOceny`),
  ADD KEY `idOferty` (`idOferty`),
  ADD KEY `idZamowienia` (`idZamowienia`);

--
-- Indeksy dla tabeli `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`idOferty`),
  ADD KEY `idUzytkownika` (`idUzytkownika`),
  ADD KEY `idKategorii` (`idKategorii`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`idUzytkownika`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`idZamowienia`),
  ADD KEY `idKoszyka` (`idKoszyka`),
  ADD KEY `idOferty` (`idOferty`);

--
-- Indeksy dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD PRIMARY KEY (`idZdjecia`),
  ADD KEY `idOferty` (`idOferty`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `idKategorii` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  MODIFY `idKoszyka` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `kwoty`
--
ALTER TABLE `kwoty`
  MODIFY `idKwoty` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `oceny`
--
ALTER TABLE `oceny`
  MODIFY `idOceny` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `oferty`
--
ALTER TABLE `oferty`
  MODIFY `idOferty` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `idUzytkownika` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `idZamowienia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `idZdjecia` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `koszyki`
--
ALTER TABLE `koszyki`
  ADD CONSTRAINT `koszyki_ibfk_1` FOREIGN KEY (`idUzytkownika`) REFERENCES `uzytkownicy` (`idUzytkownika`);

--
-- Ograniczenia dla tabeli `kwoty`
--
ALTER TABLE `kwoty`
  ADD CONSTRAINT `kwoty_ibfk_1` FOREIGN KEY (`idOferty`) REFERENCES `oferty` (`idOferty`),
  ADD CONSTRAINT `kwoty_ibfk_2` FOREIGN KEY (`idUzytkownika`) REFERENCES `uzytkownicy` (`idUzytkownika`);

--
-- Ograniczenia dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `oceny_ibfk_1` FOREIGN KEY (`idOferty`) REFERENCES `oferty` (`idOferty`),
  ADD CONSTRAINT `oceny_ibfk_2` FOREIGN KEY (`idZamowienia`) REFERENCES `zamowienia` (`idZamowienia`);

--
-- Ograniczenia dla tabeli `oferty`
--
ALTER TABLE `oferty`
  ADD CONSTRAINT `oferty_ibfk_1` FOREIGN KEY (`idUzytkownika`) REFERENCES `uzytkownicy` (`idUzytkownika`),
  ADD CONSTRAINT `oferty_ibfk_2` FOREIGN KEY (`idKategorii`) REFERENCES `kategorie` (`idKategorii`);

--
-- Ograniczenia dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_ibfk_1` FOREIGN KEY (`idKoszyka`) REFERENCES `koszyki` (`idKoszyka`),
  ADD CONSTRAINT `zamowienia_ibfk_2` FOREIGN KEY (`idOferty`) REFERENCES `oferty` (`idOferty`);

--
-- Ograniczenia dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD CONSTRAINT `zdjecia_ibfk_1` FOREIGN KEY (`idOferty`) REFERENCES `oferty` (`idOferty`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
