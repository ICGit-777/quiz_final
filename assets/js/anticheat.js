document.addEventListener("DOMContentLoaded", () => {
    const quizForm = document.querySelector("form[data-tentative-id]");
    if (!quizForm) return;

    const tentativeId = quizForm.getAttribute("data-tentative-id");
    
    // CHANGEMENT : Récupération dynamique du temps (fourni par B en minutes, défaut 10)
    const dureeMinutes = quizForm.getAttribute("data-duree-minutes") ? parseInt(quizForm.getAttribute("data-duree-minutes")) : 10;
    let tempsRestant = dureeMinutes * 60; 

    let countSorties = 0;
    const maxSortiesAutorisees = 1;

    // Bannière d'alerte
    const alertBanner = document.createElement("div");
    alertBanner.style.cssText = "position:fixed; top:0; left:0; width:100%; padding:15px; background:#ff7675; color:white; text-align:center; font-weight:bold; z-index:9999; display:none; font-family:sans-serif;";
    document.body.prepend(alertBanner);

    // Minuteur visuel
    const timerDisplay = document.createElement("div");
    timerDisplay.style.cssText = "position:fixed; top:20px; right:20px; background:#0a2e2a; color:#74d7c4; padding:10px 15px; font-size:1.2rem; font-weight:bold; border-radius:8px; z-index:999; font-family:monospace;";
    document.body.appendChild(timerDisplay);

    const formatTimer = (secondes) => {
        const mins = Math.floor(secondes / 60).toString().padStart(2, '0');
        const secs = (secondes % 60).toString().padStart(2, '0');
        return `⏱️ ${mins}:${secs}`;
    };

    const intervalMinuteur = setInterval(() => {
        tempsRestant--;
        timerDisplay.textContent = formatTimer(tempsRestant);

        if (tempsRestant <= 0) {
            clearInterval(intervalMinuteur);
            declarerIncident('temps_depasse');
            alert("Temps écoulé ! Votre quiz va être soumis automatiquement.");
            quizForm.submit();
        }
    }, 1000);

    // API Fullscreen
    const activerPleinEcran = () => {
        const docEl = document.documentElement;
        if (docEl.requestFullscreen) docEl.requestFullscreen();
        else if (docEl.webkitRequestFullscreen) docEl.webkitRequestFullscreen();
    };

    const overlay = document.createElement("div");
    overlay.style.cssText = "position:fixed; top:0; left:0; width:100%; height:100%; background:#0a2e2a; color:white; display:flex; flex-direction:column; justify-content:center; align-items:center; z-index:10000; font-family:sans-serif;";
    overlay.innerHTML = `<h2 style="margin-bottom:15px;">🔒 Mode Examen Sécurisé</h2>
                         <p style="margin-bottom:20px; color:#b2d2ce;">Ce QCM requiert le mode plein écran obligatoire (Durée : ${dureeMinutes} min).</p>
                         <button id='btn-start-quiz' style='background:#00b894; color:white; border:none; padding:12px 25px; font-size:1.1rem; font-weight:bold; border-radius:8px; cursor:pointer;'>Démarrer le Quiz</button>`;
    document.body.appendChild(overlay);

    document.getElementById("btn-start-quiz").addEventListener("click", () => {
        activerPleinEcran();
        overlay.remove();
    });

    document.addEventListener("fullscreenchange", () => {
        if (!document.fullscreenElement) {
            countSorties++;
            declarerIncident('changement_onglet');
            if (countSorties >= maxSortiesAutorisees) {
                remonterAbandonEnBase();
            } else {
                afficherAvertissement("⚠️ ATTENTION : Le plein écran est obligatoire ! Évitez de quitter la page.");
            }
        }
    });

    window.addEventListener("blur", () => {
        declarerIncident('perte_focus');
        afficherAvertissement("🚨 Alerte : Perte de focus détectée (changement d'onglet ou de fenêtre) !");
    });

    function declarerIncident(typeIncident) {
        const formData = new FormData();
        formData.append("tentative_id", tentativeId);
        formData.append("type_incident", typeIncident);
        fetch("enregistrer_incident.php", { method: "POST", body: formData });
    }

    function remonterAbandonEnBase() {
        clearInterval(intervalMinuteur);
        const formData = new FormData();
        formData.append("tentative_id", tentativeId);
        formData.append("action", "invalider_tentative");

        fetch("enregistrer_incident.php", { method: "POST", body: formData })
        .finally(() => {
            alert("❌ Tentative invalidée pour sortie du mode plein écran !");
            window.location.href = "resultat.php?statut=abandonne";
        });
    }

    function afficherAvertissement(message) {
        alertBanner.textContent = message;
        alertBanner.style.display = "block";
        setTimeout(() => { alertBanner.style.display = "none"; }, 5000);
    }
});