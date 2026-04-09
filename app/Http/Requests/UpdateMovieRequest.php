<?php declare(strict_types=1);

namespace App\Http\Requests;

use App\DTO\Admin\MovieDataDto;
use App\Enums\MovieStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateMovieRequest',
    required: [
        'title',
        'director',
        'description',
        'year',
        'genre',
        'rating',
        'status'
    ],
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Movie title 1'),
        new OA\Property(property: 'director', type: 'string', example: 'Movie director 1'),
        new OA\Property(property: 'description', type: 'string', example: 'Movie description 1'),
        new OA\Property(property: 'year', type: 'integer', example: '2027'),
        new OA\Property(property: 'genre', type: 'string', example: 'Movie genre 1'),
        new OA\Property(property: 'rating', type: 'numeric', example: '1'),
        new OA\Property(property: 'status', example: 'published', enum: MovieStatus::class),
    ],
    type: 'object'
)]
class UpdateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'slug' => 'nullable',
            'description' => 'required|string|max:1000',
            'year' => 'required|integer|min:1900|max:2050',
            'genre' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:10',
            'status' => ['required', new Enum(MovieStatus::class)],
            'image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_image' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Назва фільму обов\'язкова',
            'title.string' => 'Назва фільму має бути рядком',
            'title.max' => 'Максимальна довжина назви фільму 255 символів',
            'director.required' => 'Автор фільму обов\'язковий',
            'director.string' => 'Автор фільму має бути рядком',
            'director.max' => 'Максимальна довжина автора фільму 255 символів',
            'description.required' => 'Опис фільму обов\'язковий',
            'description.string' => 'Опис фільму має бути рядком',
            'description.max' => 'Максимальна довжина опису фільму 1000 символів',
            'year.required' => 'Дата виходу фільму обов\'язкова',
            'year.integer' => 'Дата виходу фільму має бути числом',
            'year.min' => 'Дата виходу фільму не може бути раніше 1900 року',
            'year.max' => 'Дата виходу фільму не може бути пізніше 2050 року',
            'genre.required' => 'Жанр фільму обов\'язковий',
            'genre.string' => 'Жанр фільму має бути рядком',
            'genre.max' => 'Максимальна довжина жанру 255 символів',
            'rating.required' => 'Рейтинг фільму обов\'язковий',
            'rating.integer' => 'Рейтинг фільму має бути числом',
            'rating.min' => 'Рейтинг фільму не може бути менше 0',
            'rating.max' => 'Рейтинг фільму не може бути більше 10',
            'status.required' => 'Статус фільму обов\'язковий',
            'image.image' => 'Файл має бути зображенням',
            'image.mimes' => 'Допустимі типи зображень: jpeg, png, jpg, webp',
            'image.max' => 'Максимальний розмір файлу 2 МБ',
        ];
    }

    public function toDTO(): MovieDataDto
    {
        $data = $this->validated();
        $data['status'] = MovieStatus::tryFrom($data['status']);
        $data['imageFile'] = $this->file('image');
        $data['removeImage'] = $this->boolean('remove_image');

        return MovieDataDto::fromArray($data);
    }
}
