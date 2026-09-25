const luckButton = document.querySelector(".luck")

        luckButton.addEventListener("click", (event) => {
            event.preventDefault()
            window.location.href = "traitements/luckySearch.php"
        });

    const typewriterInput = document.querySelector('.input')
    // Définir un tableau de choix
    const words = ["Visio", "Internet", "Capsule", "Emploi store"];
    let wordIndex = 0;
    let letterIndex = 0;
    let isDeleting = false;
    let isTyping = true;

    typewriterInput.addEventListener("input", (e) => {
        isTyping = false;
    });

    function typeWriter() {
        if (isTyping) {
            if (wordIndex < words.length) {
                const currentWord = words[wordIndex];
                if (!isDeleting && letterIndex < currentWord.length) {
                    typewriterInput.placeholder += currentWord.charAt(letterIndex);
                    letterIndex++;
                    setTimeout(typeWriter, 100); // temps entre chaque caractère (en millisecondes)
                } else {
                    isDeleting = true;
                    if (typewriterInput.placeholder.length > 0) {
                        typewriterInput.placeholder = typewriterInput.placeholder.slice(0, -1);
                        setTimeout(typeWriter, 200); // temps entre chaque suppression de caractère (en millisecondes)
                    } else {
                        isDeleting = false;
                        wordIndex++;
                        letterIndex = 0;
                        setTimeout(typeWriter, 300); // temps entre chaque mot (en millisecondes)
                    }
                }
            } else {
                // fin de la liste des mots, recommencer
                wordIndex = 0;
                letterIndex = 0;
                isDeleting = false;
                setTimeout(typeWriter, 1000); // temps d'attente avant de recommencer (en millisecondes)
            }
        }
    }

    typeWriter();