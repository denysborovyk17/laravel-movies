namespace App\Services\Interfaces;

use App\DTOs\{AuthDTO, LoginDTO, RegisterDTO};
use App\Models\User;

interface ApiAuthServiceInterface
{
    public function register(RegisterDTO $userDTO): AuthDTO;

    public function login(LoginDTO $userDTO): AuthDTO;

    public function logout(User $user): void;
}
