<?php

namespace Smart\Resale\Traits;

trait FlashMessageTrait
{
    private function addErrorMessage(string $errorMessage): void
    {
        $_SESSION['error_message'] = $errorMessage;
    }

    private function addSuccessMessageAlert(string $titleMessage, string $textMessage = ""): void
    {
        $_SESSION['success_title_message'] = $titleMessage;
        $_SESSION['success_text_message'] = $textMessage;
    }

    private function addErrorMessageAlert(string $titleMessage, string $textMessage = ""): void
    {
        $_SESSION['error_title_message'] = $titleMessage;
        $_SESSION['error_text_message'] = $textMessage;
    }
}
