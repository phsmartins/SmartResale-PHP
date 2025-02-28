function successMessage(title, text) {
    Swal.fire({
        title: `${title}`,
        text: `${text}`,
        icon: "success",
        width: "50em",
        padding: "2.4rem"
    });
}

function errorMessage(title, text) {
    Swal.fire({
        title: `${title}`,
        text: `${text}`,
        icon: "error",
        width: "50em",
        padding: "2.4rem"
    });
}