<?php 

namespace App\Application\DTOs;

class MemberDTO{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}
}