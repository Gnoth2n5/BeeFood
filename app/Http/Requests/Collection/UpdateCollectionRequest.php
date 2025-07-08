<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
{
  /**
   * Xác định xem người dùng có quyền thực hiện yêu cầu này hay không..
   */
  public function authorize(): bool
  {
    return $this->user()->can('update', $this->route('collection'));
  }

  /**
   * Lấy các quy tắc xác thực áp dụng cho yêu cầu.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string', 'max:1000'],
      'is_public' => ['boolean'],
      'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
    ];
  }

  /**
   * Nhận thông điệp tùy chỉnh cho lỗi xác thực.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'Tên bộ sưu tập là bắt buộc.',
      'name.max' => 'Tên bộ sưu tập không được vượt quá 255 ký tự.',
      'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
      'cover_image.image' => 'File phải là hình ảnh.',
      'cover_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
      'cover_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
    ];
  }

  /**
   * Lấy các thuộc tính tùy chỉnh cho lỗi xác thực.
   */
  public function attributes(): array
  {
    return [
      'name' => 'tên bộ sưu tập',
      'description' => 'mô tả',
      'is_public' => 'công khai',
      'cover_image' => 'hình ảnh bìa',
    ];
  }

  /**
   * Chuẩn bị dữ liệu để xác thực.
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'is_public' => $this->boolean('is_public'),
    ]);
  }
}
