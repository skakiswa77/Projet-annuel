
$(document).ready(function() {
  
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
  
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
   
    if($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'fr'
        });
    }
    
   
    if($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }
    

    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    

    if ($('#tutorial-overlay').length) {
        const tutorialSteps = $('.tutorial-step');
        let currentStep = 0;
        
        $('#tutorial-overlay').show();
        $(tutorialSteps[currentStep]).addClass('active');
        
        
        $('#tutorial-next').click(function() {
            $(tutorialSteps[currentStep]).removeClass('active');
            currentStep++;
            
            if (currentStep < tutorialSteps.length) {
                $(tutorialSteps[currentStep]).addClass('active');
                
                
                if (currentStep === tutorialSteps.length - 1) {
                    $('#tutorial-next').text('Terminer');
                }
            } else {
                
                $('#tutorial-overlay').hide();
                
                
                $.post('actions/tutorial_completed.php');
            }
        });
        
       
        $('#tutorial-skip').click(function() {
            $('#tutorial-overlay').hide();
            $.post('actions/tutorial_skipped.php');
        });
    }
    
    
    $('.chatbot-button').click(function() {
        $('.chatbot-window').toggleClass('active');
    });
    
    $('#chatbot-form').submit(function(e) {
        e.preventDefault();
        
        const userMessage = $('#chatbot-input').val();
        if (!userMessage.trim()) return;
        
        
        appendMessage('user', userMessage);
        
        
        $('#chatbot-input').val('');
        
       
        $.ajax({
            url: 'api/chatbot.php',
            method: 'POST',
            data: { message: userMessage },
            dataType: 'json',
            success: function(response) {
                
                appendMessage('bot', response.message);
            },
            error: function() {
                appendMessage('bot', "Désolé, je rencontre des difficultés techniques. Veuillez réessayer plus tard.");
            }
        });
    });
    
    function appendMessage(sender, message) {
        const messageClass = sender === 'user' ? 'chatbot-message-user' : 'chatbot-message-bot';
        const messageHtml = `<div class="chatbot-message ${messageClass}">${message}</div>`;
        $('.chatbot-body').append(messageHtml);
        
      
        $('.chatbot-body').scrollTop($('.chatbot-body')[0].scrollHeight);
    }
    
  
    $('#language-selector').change(function() {
        const lang = $(this).val();
        window.location.href = `?lang=${lang}`;
    });
  
    if (typeof Chart !== 'undefined') {
    
        const ctxEvents = document.getElementById('eventsChart');
        if (ctxEvents) {
            new Chart(ctxEvents, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Événements',
                        data: [12, 19, 15, 17, 20, 25, 22, 18, 24, 28, 30, 35],
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        

        const ctxEventTypes = document.getElementById('eventTypesChart');
        if (ctxEventTypes) {
            new Chart(ctxEventTypes, {
                type: 'doughnut',
                data: {
                    labels: ['Bien-être', 'Conférences', 'Sportif', 'Médical', 'Solidaire'],
                    datasets: [{
                        data: [35, 20, 25, 15, 5],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    }
    
    if (typeof FullCalendar !== 'undefined') {
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                locale: 'fr',
                eventSources: [
                    {
                        url: 'api/events.php',
                        method: 'GET',
                        failure: function() {
                            alert('Impossible de charger les événements');
                        }
                    }
                ],
                eventClick: function(info) {
                    window.location.href = 'index.php?page=event&id=' + info.event.id;
                }
            });
            calendar.render();
        }
    }
});