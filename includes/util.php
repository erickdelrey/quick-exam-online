<?php

function sanitizeInputText($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = trim($inputText);
    return $inputText;
}