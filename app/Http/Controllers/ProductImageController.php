<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\TokenAuthenticatable;
use App\Models\ProductImage;
use App\Models\Product;
use App\Models\User;

class ProductImageController extends Controller
{
    use TokenAuthenticatable;

    public function saveImage(Request $request)
    {
        try {
            $user = $this->authenticateUserByToken($request);
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validatedData = $request->validate([
                'image' => 'required|image|max:2048',
                'product_id' => 'required|integer',
            ]);
            $image = $request->file('image');

            $product = Product::find($validatedData['product_id']);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Verifica se já existe uma imagem para o produto
            $existingImage = ProductImage::where('product_id', $validatedData['product_id'])->first();

            // Se existir, exclua a imagem antiga do disco
            if ($existingImage && Storage::disk('public')->exists($existingImage->image_path)) {
                Storage::disk('public')->delete($existingImage->image_path);
            }

            // Salva a nova imagem
            $path = Storage::disk('public')->putFile('images/products', $image);

            if (!$path) {
                throw new \Exception('Failed to save image to disk');
            }

            // Atualiza o caminho da imagem existente ou cria um novo registro de imagem
            if ($existingImage) {
                $existingImage->image_path = $path;
                $existingImage->save();

                return response()->json(['message' => 'Image updated successfully'], 200);
            }

            $productImage = new ProductImage();
            $productImage->product_id = $validatedData['product_id'];
            $productImage->image_path = $path;
            $productImage->save();

            return response()->json(['message' => 'Image uploaded successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to save image: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to save image'], 500);
        }
    }

}
