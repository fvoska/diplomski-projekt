JSON objekt ima sljedeće ključeve

ip - ip adresa korisnika
userID - HascheckUserID koji se dohvaća iz cookieja
requestTime - vrijeme zahtjeva u formatu ("%Y-%m-%d %H:%M:%S.%f")  --AKO TREBA MOGU PROMIJENITI U NEKI DRUGI FORMAT
context - je li uključena kontekstualna provjera. value može biti "on" ili "off"
wordCounter - broj riječi koje su poslane na provjeru
report - izvještaj koji sadrži niz rječnika koji imaju ključeve:
        severity - vrsta pogreške (gg,mm...)
        occurences - broj pojavljivanja te pogreške
        suspicious - sumnjiva riječ
        suggestions - prijedlozi za ispravljenu riječ (to je array [])
