(function () {
    // exam-interface.js - externalized Alpine.js component
    // Expects runtime data in window.__exam_data

    function createExamInterface(data) {
        return {
            // Config
            examId: data.examId,
            takenExamId: data.takenExamId,
            questions: data.questions,
            savedAnswers: data.savedAnswers || {},

            // State
            currentQuestionIndex: 0,
            answers: {},
            shuffledOptions: {},
            isSaving: false,
            isSubmitting: false,
            showSubmitModal: false,

            // Timer
            timeRemaining: data.timeRemaining || 0,
            totalTime: data.totalTime || 0,
            timerInterval: null,

            // Activity monitoring
            activityLoggingEnabled: data.activityLogging || false,
            tabSwitchCount: 0,
            windowBlurCount: 0,

            init() {
                // Load saved answers
                this.loadSavedAnswers();

                // Ensure matching answers initialized
                this.questions.forEach((q) => {
                    if (q.type === "matching" && !this.answers[q.id])
                        this.answers[q.id] = {};
                });

                this.startTimer();

                // Setup activity monitoring if enabled
                if (this.activityLoggingEnabled) {
                    this.setupActivityMonitoring();
                }

                if (typeof this.debounce !== "function") {
                    this.debounce = (func, wait) => {
                        let timeout;
                        return (...args) => {
                            clearTimeout(timeout);
                            timeout = setTimeout(
                                () => func.apply(this, args),
                                wait
                            );
                        };
                    };
                }

                this.debouncedSave = this.debounce(
                    () => this.saveAnswer(),
                    1000
                );
            },

            // Computeds
            get currentQuestion() {
                return this.questions[this.currentQuestionIndex] || null;
            },
            get totalQuestions() {
                return this.questions.length;
            },
            get answeredCount() {
                return Object.keys(this.answers).filter((k) => {
                    const answer = this.answers[k];
                    const question = this.questions.find(
                        (q) => q.id === parseInt(k)
                    );
                    if (question && question.type === "matching") {
                        if (typeof answer !== "object" || answer === null)
                            return false;
                        const pairCount = question.pairs?.length || 0;
                        const answeredPairs = Object.keys(answer).filter(
                            (x) => answer[x] !== null && answer[x] !== ""
                        ).length;
                        return answeredPairs === pairCount;
                    }
                    return (
                        answer !== null && answer !== undefined && answer !== ""
                    );
                }).length;
            },

            get unansweredCount() {
                return this.totalQuestions - this.answeredCount;
            },
            get progressPercentage() {
                return this.totalQuestions > 0
                    ? (this.answeredCount / this.totalQuestions) * 100
                    : 0;
            },
            get timePercentage() {
                return this.totalTime > 0
                    ? (this.timeRemaining / this.totalTime) * 100
                    : 0;
            },

            // Utilities
            formatTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                return `${h.toString().padStart(2, "0")}:${m
                    .toString()
                    .padStart(2, "0")}:${s.toString().padStart(2, "0")}`;
            },

            getTypeLabel(type) {
                const labels = {
                    mcq: "Multiple Choice",
                    truefalse: "True/False",
                    fillblank: "Fill in the Blank",
                    fill_blank: "Fill in the Blank",
                    shortanswer: "Short Answer",
                    essay: "Essay",
                    matching: "Matching",
                };
                return labels[type] || type;
            },

            // Load saved
            loadSavedAnswers() {
                this.answers = {};
                const saved = this.savedAnswers || {};
                for (const [id, answer] of Object.entries(saved)) {
                    const nid = parseInt(id);
                    if (
                        answer === null ||
                        answer === undefined ||
                        answer === ""
                    )
                        continue;
                    const question = this.questions.find((q) => q.id === nid);
                    if (!question) {
                        this.answers[nid] = answer;
                        continue;
                    }

                    if (
                        question.type === "mcq" &&
                        typeof answer === "string" &&
                        !isNaN(answer)
                    )
                        this.answers[nid] = parseInt(answer);
                    else if (
                        question.type === "matching" &&
                        typeof answer === "string"
                    ) {
                        try {
                            const parsed = JSON.parse(answer);
                            const conv = {};
                            for (const [l, r] of Object.entries(parsed)) {
                                const rn = parseInt(r);
                                if (question.pairs && question.pairs[rn])
                                    conv[parseInt(l)] =
                                        question.pairs[rn].right;
                            }
                            this.answers[nid] = conv;
                        } catch (e) {
                            this.answers[nid] = {};
                        }
                    } else this.answers[nid] = answer;
                }
            },

            // Navigation
            goToQuestion(i) {
                this.currentQuestionIndex = i;
            },
            previousQuestion() {
                if (this.currentQuestionIndex > 0) this.currentQuestionIndex--;
            },
            nextQuestion() {
                if (this.currentQuestionIndex < this.totalQuestions - 1)
                    this.currentQuestionIndex++;
            },

            // Timer
            startTimer() {
                this.timerInterval = setInterval(() => {
                    if (this.timeRemaining > 0) this.timeRemaining--;
                    if (this.timeRemaining <= 0) {
                        clearInterval(this.timerInterval);
                        this.autoSubmit();
                    }
                }, 1000);
            },

            // Matching helpers
            getMatchingAnswer(questionId, pairIndex) {
                if (!this.answers[questionId]) this.answers[questionId] = {};
                return this.answers[questionId][pairIndex] || "";
            },
            updateMatchingAnswer(questionId, pairIndex, value) {
                if (!this.answers[questionId]) this.answers[questionId] = {};
                this.answers[questionId][pairIndex] = value;
                this.saveAnswer();
            },
            populateMatchingDropdown(selectEl, questionId, pairIndex, pairs) {
                const shuffled = this.getShuffledRightOptions(pairs);
                shuffled.forEach((opt) => {
                    const o = document.createElement("option");
                    o.value = o.textContent = opt;
                    selectEl.appendChild(o);
                });
                selectEl.value = this.answers[questionId]?.[pairIndex] || "";
            },
            getShuffledRightOptions(pairs) {
                const key = JSON.stringify(pairs);
                if (this.shuffledOptions[key]) return this.shuffledOptions[key];
                const opts = pairs.map((p) => p.right);
                const s = [...opts];
                for (let i = s.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [s[i], s[j]] = [s[j], s[i]];
                }
                this.shuffledOptions[key] = s;
                return s;
            },

            // Save
            async saveAnswer() {
                if (
                    !this.currentQuestion ||
                    !this.answers[this.currentQuestion.id]
                )
                    return;
                this.isSaving = true;
                let answerToSave = this.answers[this.currentQuestion.id];
                if (
                    this.currentQuestion.type === "matching" &&
                    typeof answerToSave === "object"
                ) {
                    const idxAns = {};
                    for (const [l, rt] of Object.entries(answerToSave)) {
                        const rid = this.currentQuestion.pairs.findIndex(
                            (p) => p.right === rt
                        );
                        if (rid !== -1) idxAns[l] = rid.toString();
                    }
                    answerToSave = JSON.stringify(idxAns);
                }
                try {
                    const csrf = document.querySelector(
                        'meta[name="csrf-token"]'
                    );
                    if (!csrf) return;
                    const res = await fetch(
                        `/student/taken-exams/${this.takenExamId}/save-answer`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf.content,
                            },
                            body: JSON.stringify({
                                item_id: this.currentQuestion.id,
                                answer: answerToSave,
                            }),
                        }
                    );
                    if (!res.ok) throw new Error("save failed");
                } catch (e) {
                    /* silent */
                } finally {
                    setTimeout(() => {
                        this.isSaving = false;
                    }, 500);
                }
            },

            // Submit
            async submitExam() {
                this.isSubmitting = true;
                try {
                    const csrf = document.querySelector(
                        'meta[name="csrf-token"]'
                    );
                    if (!csrf) {
                        alert("Session expired. Please refresh the page.");
                        return;
                    }
                    const res = await fetch(
                        `/student/taken-exams/${this.takenExamId}/submit`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf.content,
                            },
                        }
                    );
                    if (res.redirected) window.location.href = res.url;
                    else {
                        const d = await res.json();
                        if (d.success)
                            window.location.href = `/student/taken-exams/${this.takenExamId}`;
                    }
                } catch (e) {
                    alert("Failed to submit exam. Please try again.");
                    this.isSubmitting = false;
                }
            },
            autoSubmit() {
                alert("Time is up! Your exam will be automatically submitted.");
                this.submitExam();
            },
            handleBeforeUnload(e) {
                if (!this.isSubmitting) {
                    e.preventDefault();
                    e.returnValue =
                        "You have unsaved changes. Are you sure you want to leave?";
                }
            },

            // Activity monitoring
            setupActivityMonitoring() {
                document.addEventListener("visibilitychange", () => {
                    if (document.hidden) {
                        this.logActivity("visibility_change");
                    }
                });
                window.addEventListener("blur", () => {
                    this.windowBlurCount++;
                    this.logActivity("window_blur");
                });
            },

            async logActivity(eventType) {
                if (!this.activityLoggingEnabled) return;

                try {
                    const csrf = document.querySelector(
                        'meta[name="csrf-token"]'
                    );
                    if (!csrf) return;

                    await fetch(
                        `/student/taken-exams/${this.takenExamId}/activity`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf.content,
                            },
                            body: JSON.stringify({
                                event_type: eventType,
                                timestamp: new Date().toISOString(),
                            }),
                        }
                    );
                } catch (e) {
                    /* silent */
                }
            },
        };
    }

    // Expose factory for Alpine
    window.createExamInterface = createExamInterface;

    // If page has inline data, initialize Alpine component automatically
    document.addEventListener("alpine:init", () => {
        if (window.__exam_data) {
            Alpine.data("examInterface", () =>
                createExamInterface(window.__exam_data)
            );
        }
    });
})();
