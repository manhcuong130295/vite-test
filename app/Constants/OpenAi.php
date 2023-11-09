<?php

namespace App\Constants;

interface OpenAi
{
    /**
     * Chat response by stream.
     */
    public const CHAT_STREAM_RESPONE = 1;

    /**
     * Chat response by text.
     */
    public const CHAT_TEXT_RESPONSE = 0;

    /**
     * Error message
     */
    public const ERROR_MESSAGE ='Oops! Something went wrong. Please try again later.';

    /**
     * Error message
     */
    public const LIMIT_MESSAGE ='The maximum amount of messages you can receive this month has been reached. If you want to keep utilizing. Please update your plan.';

    /**
     * Error message
     */
    public const PROJECT_NOT_FOUND = 'Chat bot is no longer available.';
}
