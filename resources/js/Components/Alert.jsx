import Swal from 'sweetalert2';

function Toast(titulo){
    Swal.fire({
        toast: true, // Define como um toast
        position: 'center', // Posição (ex: canto superior direito)
        showConfirmButton: false, 
        allowOutsideClick: false, // IMPEDE que o utilizador feche clicando fora
        allowEscapeKey: false,// Não mostra o botão de confirmação // Tempo grande para simular o "aguarde" (feche manualmente depois)
        timerProgressBar: true, // Mostra a barra de progresso do timer
        title: titulo,
        didOpen: (toast) => {
            Swal.showLoading();
        }
 
});

}
function ErrorAlert(error){
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: error
    });
}
function Close(){
    Swal.close()
}
export default {Toast, ErrorAlert, Close}