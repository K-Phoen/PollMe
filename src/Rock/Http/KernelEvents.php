<?php

namespace Rock\Http;


class KernelEvents
{
    // when the kernel receives a request
    const REQUEST = 'kernel.request';

    // when a controller is found
    const CONTROLLER = 'kernel.controller';
}
