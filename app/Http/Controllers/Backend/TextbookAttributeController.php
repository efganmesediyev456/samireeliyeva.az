<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Textbook;
use App\Models\TextbookAttribute;
use App\Models\TextbookAttributeTranslation;
use Attribute;
use Illuminate\Http\Request;

class TextbookAttributeController extends Controller
{
    public function index(Textbook $textbook)
    {
        $attributes = $textbook->attributes()
            ->with('translations')
            ->get();
            
        return view('backend.pages.textbook-attributes.index', compact('textbook', 'attributes'));
    }
    
    public function create(Textbook $textbook)
    {
        return view('backend.pages.textbook-attributes.create', compact('textbook'));
    }
    
    public function store(Request $request, Textbook $textbook)
    {
        $request->validate([
            'az_key' => 'required|string',
            'az_value' => 'required|string',
        ]);
        
        $attribute = $textbook->attributes()->create([]);
        
        foreach(['az', 'en', 'ru'] as $locale) {
            $attribute->translations()->create([
                'locale' => $locale,
                'key' => $request->input($locale . '_key'),
                'value' => $request->input($locale . '_value'),
            ]);
        }
        
        return redirect()
            ->route('admin.textbooks.attributes.index', $textbook)
            ->with('success', 'Atribut uğurla əlavə edildi');
    }
    
    public function edit( $textbook,  $attribute)
    {
        $textbook = Textbook::find($textbook);
        $attribute = TextbookAttribute::find($attribute);
        $attribute->load('translations');
        return view('backend.pages.textbook-attributes.edit', compact('textbook', 'attribute'));
    }
    
    public function update(Request $request, $textbook,$attribute)
    {
        $attribute = TextbookAttribute::find($attribute);

        $request->validate([
            'az_key'   => 'required|string',
            'az_value' => 'required|string',
        ]);
        
        foreach(['az', 'en', 'ru'] as $locale) {
            $attribute->translations()
                ->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'key' => $request->input($locale . '_key'),
                        'value' => $request->input($locale . '_value'),
                    ]
                );
        }
        
        return redirect()
            ->route('admin.textbooks.attributes.index', $textbook)
            ->with('success', 'Atribut uğurla yeniləndi');
    }
    
    public function destroy($textbook, $attribute)
    {
     
        $attribute = TextbookAttribute::find($attribute);
        $attribute->delete();
        
        return redirect()
            ->route('admin.textbooks.attributes.index', $textbook)
            ->with('success', 'Atribut silindi');
    }
}
