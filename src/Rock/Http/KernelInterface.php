<?php

namespace Rock\Http;


interface KernelInterface
{
    public function handle(Request $request);
}
