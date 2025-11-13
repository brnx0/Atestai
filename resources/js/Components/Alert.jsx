import Swal from 'sweetalert2';

function Toast(titulo){
    Swal.fire({
        toast: true, // Define como um toast
        position: 'center', // Posição (ex: canto superior direito)
        showConfirmButton: false, // Não mostra o botão de confirmação
        timer: 90000, // Tempo grande para simular o "aguarde" (feche manualmente depois)
        timerProgressBar: true, // Mostra a barra de progresso do timer
        title: titulo,
        didOpen: (toast) => {
            // 2. Esta função é executada quando o Toast é aberto.
            // Aqui, adicionamos a classe de carregamento (spinner)
            Swal.showLoading();
            // Você também pode adicionar a classe diretamente no Toast container se preferir
            // toast.querySelector('.swal2-popup').classList.add('swal2-loading');
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