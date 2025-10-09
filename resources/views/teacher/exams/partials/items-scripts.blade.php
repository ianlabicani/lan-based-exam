<script>
    // Store items data for editing
    const examItems = @json($examItems);
    const examId = {{ $exam->id }};

    function showAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.remove('hidden');
    }

    function hideAddQuestionModal() {
        document.getElementById('addQuestionModal').classList.add('hidden');
        hideAllQuestionForms();
    }

    function showDeleteModal(itemId, questionText) {
        document.getElementById('deleteQuestionText').textContent = questionText;
        document.getElementById('deleteQuestionForm').action = `/teacher/exams/${examId}/items/${itemId}?tab=items`;
        document.getElementById('deleteQuestionModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('deleteQuestionModal').classList.add('hidden');
    }

    function hideAllQuestionForms() {
        document.querySelectorAll('[id$="QuestionForm"]').forEach(form => {
            form.classList.add('hidden');
        });
    }

    function showQuestionForm(type) {
        hideAllQuestionForms();
        document.getElementById(type + 'QuestionForm').classList.remove('hidden');
    }

    function editQuestion(itemId, type) {
        const item = examItems.find(i => i.id === itemId);
        if (!item) {
            alert('Question not found');
            return;
        }

        // Map database type to form type
        const typeMap = {
            'mcq': 'mcq',
            'truefalse': 'trueFalse',
            'shortanswer': 'shortAnswer',
            'essay': 'essay',
            'matching': 'matching',
            'fillblank': 'fillBlank',
            'fill_blank': 'fillBlank'
        };

        const formType = typeMap[type] || type;
        const formId = 'edit' + formType.charAt(0).toUpperCase() + formType.slice(1) + 'QuestionForm';

        // Set the form action for this item
        if (type === 'mcq') {
            setEditMcqAction(examId, itemId);
            populateEditMcqForm(item);
        } else if (type === 'truefalse') {
            setEditTrueFalseAction(examId, itemId);
            populateEditTrueFalseForm(item);
        } else if (type === 'shortanswer') {
            setEditShortAnswerAction(examId, itemId);
            populateEditShortAnswerForm(item);
        } else if (type === 'essay') {
            setEditEssayAction(examId, itemId);
            populateEditEssayForm(item);
        } else if (type === 'matching') {
            setEditMatchingAction(examId, itemId);
            populateEditMatchingForm(item);
        } else if (type === 'fillblank' || type === 'fill_blank') {
            setEditFillBlankAction(examId, itemId);
            populateEditFillBlankForm(item);
        }

        // Show the edit form
        document.getElementById(formId).classList.remove('hidden');
    }
</script>
