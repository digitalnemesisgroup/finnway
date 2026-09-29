<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-[calc(100vh-60px)] flex flex-col" x-data="productWizard()">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center gap-4 px-4 py-4 justify-between">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="<?php echo e(route('seller.products')); ?>" class="w-9 h-9 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">
                    <i class="ri-arrow-left-line text-lg"></i>
                </a>
                <h1 class="text-base font-bold text-[#212121]">Add New Product</h1>
            </div>
            
            <!-- Wizard Progress -->
            <div class="flex justify-between relative w-full sm:w-96 px-4">
                <div class="absolute top-4 left-6 right-6 h-0.5 bg-slate-200 -z-10"></div>
                <div class="absolute top-4 left-6 h-0.5 bg-[#006837] transition-all duration-300 -z-10" :style="`width: calc(${(step - 1) / 3 * 100}% * (100% - 3rem) / 100)`"></div>
                <template x-for="i in 4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors border-2 bg-white z-10"
                         :class="step > i ? 'bg-[#388e3c] border-[#388e3c] text-white' : step === i ? 'bg-[#006837] border-[#006837] text-white' : 'border-slate-300 text-slate-400'"
                         x-text="step > i ? '✓' : i"></div>
                </template>
            </div>
        </div>
    </div>

    <form action="<?php echo e(route('seller.products.store')); ?>" method="POST" enctype="multipart/form-data" id="productForm" class="flex-1 flex flex-col">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="seller_type" :value="formData.seller_type || '<?php echo e(Auth::user()->seller_type ?: "Retailer"); ?>'">

        
        <div class="flex-1 p-4 overflow-y-auto pb-24 max-w-3xl mx-auto w-full">
            <!-- STEP 1: Condition & Basic Details -->
            <div x-show="step === 1" x-transition.opacity>
                <h2 class="text-xl font-bold text-slate-900 mb-6">Product Condition & Basics</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Product Condition <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex flex-col items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.condition_type === 'new' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-100 bg-white text-slate-600'">
                                <input type="radio" name="condition_type" value="new" class="sr-only" x-model="formData.condition_type" @change="fetchCategoryFields">
                                <i class="ri-sparkling-fill text-2xl mb-1"></i>
                                <span class="font-bold text-sm">New Product</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.condition_type === 'old' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-slate-100 bg-white text-slate-600'">
                                <input type="radio" name="condition_type" value="old" class="sr-only" x-model="formData.condition_type" @change="fetchCategoryFields">
                                <i class="ri-recycle-fill text-2xl mb-1"></i>
                                <span class="font-bold text-sm">Old / Used Product</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" class="input bg-white w-full p-3 border rounded-md" required x-model="formData.category_id" @change="fetchCategoryFields">
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="input w-full p-3 border rounded-md" placeholder="e.g. iPhone 13 Pro" required x-model="formData.name">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Brand</label>
                                <input type="text" name="brand" class="input w-full p-3 border rounded-md" placeholder="e.g. Apple" x-model="formData.brand">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Model</label>
                                <input type="text" name="model" class="input w-full p-3 border rounded-md" placeholder="e.g. A2638" x-model="formData.model">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Photos <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="upload-zone !p-0 aspect-square flex flex-col items-center justify-center bg-indigo-50 border-indigo-200 border rounded-xl cursor-pointer">
                                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="handleFiles" required>
                                    <i class="ri-camera-fill text-2xl text-indigo-500 mb-1"></i>
                                    <span class="text-[0.65rem] font-semibold text-indigo-600">Add Photo</span>
                                </label>
                                <template x-for="(img, idx) in previewImages" :key="idx">
                                    <div class="aspect-square rounded-xl overflow-hidden relative">
                                        <img :src="img" class="w-full h-full object-cover">
                                        <button type="button" @click="removeImage(idx)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow-md"><i class="ri-close-line"></i></button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea name="description" class="input w-full p-3 border rounded-md" placeholder="Describe product details..." rows="3" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Category Specific Fields -->
            <div x-show="step === 2" x-cloak x-transition.opacity>
                <h2 class="text-xl font-bold text-slate-900 mb-6">Specific Details</h2>
                
                <div x-show="loadingFields" class="text-center py-4">
                    <i class="ri-loader-4-line animate-spin text-3xl text-indigo-500"></i>
                    <p class="text-sm text-slate-500 mt-2">Loading specific fields...</p>
                </div>

                <div x-show="!loadingFields" class="space-y-4">
                    <template x-if="dynamicFields.length === 0">
                        <div class="bg-blue-50 p-4 rounded-xl text-blue-700 text-sm">
                            No specific details required for this category.
                        </div>
                    </template>
                    <template x-for="field in dynamicFields" :key="field.id">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">
                                <span x-text="field.name"></span>
                                <span x-show="field.is_required" class="text-red-500">*</span>
                            </label>
                            
                            <template x-if="field.input_type === 'select'">
                                <select :name="'meta[' + field.name + ']'" class="input w-full p-3 border rounded-md bg-white" :required="field.is_required">
                                    <option value="">Select Option</option>
                                    <template x-for="opt in (field.options ? field.options.split(',').map(s => s.trim()) : [])">
                                        <option :value="opt" x-text="opt"></option>
                                    </template>
                                </select>
                            </template>
                            
                            <template x-if="field.input_type !== 'select'">
                                <input :type="field.input_type === 'number' ? 'number' : 'text'" 
                                       :name="'meta[' + field.name + ']'" 
                                       class="input w-full p-3 border rounded-md" 
                                       :required="field.is_required"
                                       :min="field.input_type === 'number' ? field.min_val : null"
                                       :max="field.input_type === 'number' ? field.max_val : null"
                                       :minlength="field.input_type === 'text' ? field.min_val : null"
                                       :maxlength="field.input_type === 'text' ? field.max_val : null">
                            </template>
                        </div>
                    </template>
                </div>
                
                <!-- Extra fields for Old products -->
                <div x-show="formData.condition_type === 'old'" class="space-y-4 mt-6 bg-orange-50 p-4 rounded-xl border border-orange-100">
                    <h3 class="font-bold text-slate-800">Used Product Details</h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">How old is it? (Months)</label>
                        <input type="number" name="product_age_months" class="input w-full p-3 border rounded-md" placeholder="e.g. 12">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Available Documents</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="bill_available" value="1" class="w-4 h-4 text-indigo-600 rounded">
                                <span class="text-sm font-medium">Original Bill</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="warranty_available" value="1" class="w-4 h-4 text-indigo-600 rounded" x-model="formData.has_warranty">
                                <span class="text-sm font-medium">Under Warranty</span>
                            </label>
                        </div>
                    </div>
                    <div x-show="formData.has_warranty">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Warranty Details</label>
                        <input type="text" name="warranty_info" class="input w-full p-3 border rounded-md" placeholder="e.g. 6 months remaining">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Any Damage / Repairs?</label>
                        <textarea name="damage_details" class="input w-full p-3 border rounded-md" placeholder="Describe any scratches, dents, or past repairs..." rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Pricing & Delivery -->
            <div x-show="step === 3" x-cloak x-transition.opacity>
                <h2 class="text-xl font-bold text-slate-900 mb-6">Pricing & Delivery</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Expected / Selling Price (₹) <span class="text-red-500">*</span></label>
                        <input type="number" name="selling_price" class="input w-full p-3 border rounded-md text-xl font-black text-indigo-600" placeholder="0" required x-model="formData.price">
                    </div>
                    
                    <!-- Commission Calculator -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 mt-2" x-show="formData.price > 0">
                        <h4 class="text-sm font-bold text-blue-900 mb-3">Earnings Breakdown</h4>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-blue-700">Product Price</span>
                            <span class="font-medium">₹<span x-text="formData.price"></span></span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-3">
                            <span class="text-blue-700">FIINWAY Commission <span x-text="getCommissionText()"></span></span>
                            <span class="font-medium text-red-500">-₹<span x-text="getCommissionAmount()"></span></span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2" x-show="getOtherFee() > 0">
                            <span class="text-blue-700">Other Applicable Fee</span>
                            <span class="font-medium text-red-500">-₹<span x-text="getOtherFee()"></span></span>
                        </div>
                        <div class="border-t border-blue-200 pt-3 flex justify-between items-center">
                            <span class="font-bold text-blue-900">Estimated Amount You Receive</span>
                            <span class="font-black text-green-600 text-lg">₹<span x-text="Math.max(0, formData.price - getCommissionAmount() - getOtherFee())"></span></span>
                        </div>
                    </div>
                    
                    <div x-show="formData.condition_type === 'new'">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Stock Quantity <span class="text-red-500" x-show="formData.condition_type === 'new'">*</span></label>
                        <input type="number" name="stock" class="input w-full p-3 border rounded-md" placeholder="e.g. 10" min="1" :required="formData.condition_type === 'new'" x-model="formData.stock">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Location (City) <span class="text-red-500">*</span></label>
                        <input type="text" name="city" class="input w-full p-3 border rounded-md" placeholder="Enter your city" required x-model="formData.city">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Delivery Method <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <label class="flex flex-col justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.delivery_partner_type === 'seller' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 bg-white'">
                                <input type="radio" name="delivery_partner_type" value="seller" class="sr-only" x-model="formData.delivery_partner_type" @change="formData.delivery_type = 'courier'">
                                <div class="flex items-center gap-2 mb-1 text-indigo-700">
                                    <i class="ri-user-star-line text-lg"></i>
                                    <span class="font-bold text-sm">Self Delivery</span>
                                </div>
                                <span class="text-xs text-slate-500">You will deliver the product to the buyer</span>
                            </label>
                            
                            <label class="flex flex-col justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.delivery_partner_type === 'fiinway' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 bg-white'">
                                <input type="radio" name="delivery_partner_type" value="fiinway" class="sr-only" x-model="formData.delivery_partner_type" @change="formData.delivery_type = 'courier'">
                                <div class="flex items-center gap-2 mb-1 text-indigo-700">
                                    <i class="ri-truck-line text-lg"></i>
                                    <span class="font-bold text-sm">FIINWAY Delivery</span>
                                </div>
                                <span class="text-xs text-slate-500">FIINWAY partner will pick up and deliver</span>
                            </label>

                            <label class="flex flex-col justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.delivery_partner_type === 'third_party' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 bg-white'">
                                <input type="radio" name="delivery_partner_type" value="third_party" class="sr-only" x-model="formData.delivery_partner_type" @change="formData.delivery_type = 'courier'">
                                <div class="flex items-center gap-2 mb-1 text-indigo-700">
                                    <i class="ri-rocket-line text-lg"></i>
                                    <span class="font-bold text-sm">Third-Party Courier</span>
                                </div>
                                <span class="text-xs text-slate-500">You will use an external courier service</span>
                            </label>
                            
                            <label class="flex flex-col justify-center p-3 border-2 rounded-xl cursor-pointer transition-all" :class="formData.delivery_partner_type === 'buyer_pickup' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 bg-white'">
                                <input type="radio" name="delivery_partner_type" value="buyer_pickup" class="sr-only" x-model="formData.delivery_partner_type" @change="formData.delivery_type = 'self'">
                                <div class="flex items-center gap-2 mb-1 text-indigo-700">
                                    <i class="ri-store-2-line text-lg"></i>
                                    <span class="font-bold text-sm">Self Pickup</span>
                                </div>
                                <span class="text-xs text-slate-500">Buyer will pick up from your location</span>
                            </label>
                        </div>
                        
                        <!-- Hidden inputs for backend compatibility if they choose buyer pickup -->
                        <input type="hidden" name="delivery_type" :value="formData.delivery_type">
                        <input type="hidden" name="pickup_available" :value="formData.delivery_partner_type === 'buyer_pickup' ? 1 : 0">
                    </div>
                </div>
            </div>

            <!-- STEP 4: Review & Publish -->
            <div x-show="step === 4" x-cloak x-transition.opacity>
                <h2 class="text-xl font-bold text-slate-900 mb-6">Review & Publish</h2>
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Photos Preview -->
                    <div class="flex overflow-x-auto gap-2 p-4 bg-slate-50 border-b border-slate-100">
                        <template x-for="(img, idx) in previewImages" :key="idx">
                            <img :src="img" class="h-20 w-20 object-cover rounded-lg flex-shrink-0 shadow-sm border border-slate-200">
                        </template>
                    </div>
                    
                    <div class="p-4 space-y-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Product Name</span>
                            <p class="font-bold text-lg text-slate-900" x-text="formData.name"></p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Condition</span>
                                <p class="font-medium text-slate-800" x-text="formData.condition_type === 'new' ? 'New Product' : 'Old / Used Product'"></p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Category</span>
                                <p class="font-medium text-slate-800" x-text="getCategoryName()"></p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Brand</span>
                                <p class="font-medium text-slate-800" x-text="formData.brand || 'N/A'"></p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Model</span>
                                <p class="font-medium text-slate-800" x-text="formData.model || 'N/A'"></p>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-100 pt-4 grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expected Earnings</span>
                                <p class="font-black text-xl text-green-600">₹<span x-text="Math.max(0, formData.price - getCommissionAmount() - getOtherFee())"></span></p>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Delivery By</span>
                                <p class="font-medium text-slate-800" x-text="getDeliveryText()"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100 mt-6 flex gap-3">
                    <i class="ri-checkbox-circle-fill text-indigo-500 text-xl"></i>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-900 mb-1">Ready to go live!</h4>
                        <p class="text-xs text-indigo-700 leading-relaxed">By clicking "Publish Product", your product will instantly become live on the FIINWAY marketplace.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="max-w-3xl mx-auto flex gap-3">
                <button type="button" class="flex-1 py-3 border border-slate-300 text-[#212121] font-bold text-sm rounded-sm hover:bg-slate-50" x-show="step > 1" @click="step--">Back</button>
                <button type="button" class="flex-1 py-3 bg-[#e94f1c] hover:bg-[#cc4214] text-white font-bold text-sm rounded-sm shadow" x-show="step < 4" @click="validateStep()">Next Step <i class="ri-arrow-right-line ml-1"></i></button>
                <button type="submit" class="flex-1 py-3 bg-[#388e3c] hover:bg-green-700 text-white font-bold text-sm rounded-sm shadow" x-show="step === 4" id="submitBtn">Publish Product <i class="ri-upload-cloud-2-line ml-1"></i></button>
            </div>
        </div>
    </form>
</div>

<script>
const categoriesData = <?php echo json_encode($categories, 15, 512) ?>;

document.addEventListener('alpine:init', () => {
    Alpine.data('productWizard', () => ({
        step: 1,
        previewImages: [],
        dynamicFields: [],
        loadingFields: false,
        formData: {
            seller_type: '<?php echo e(Auth::user()->seller_type ?? ""); ?>',
            condition_type: 'new',
            category_id: '',
            name: '',
            brand: '',
            model: '',
            city: '',
            has_warranty: false,
            price: '',
            stock: 1,
            delivery_type: 'courier',
            delivery_partner_type: 'seller'
        },
        
        getDeliveryText() {
            const map = {
                'seller': 'Self Delivery (By You)',
                'fiinway': 'FIINWAY Delivery',
                'third_party': 'Third-Party Courier',
                'buyer_pickup': 'Self Pickup (By Buyer)'
            };
            return map[this.formData.delivery_partner_type] || 'Courier';
        },
        
        getOtherFee() {
            const cat = this.getCategory();
            if(!cat) return 0;
            return parseFloat(cat.other_fee) || 0;
        },
        
        getCategory() {
            if(!this.formData.category_id) return null;
            return categoriesData.find(c => c.id == this.formData.category_id);
        },
        
        getCategoryName() {
            const cat = this.getCategory();
            return cat ? cat.name : 'N/A';
        },
        
        getCommissionAmount() {
            const cat = this.getCategory();
            if(!cat || !this.formData.price) return 0;
            
            const val = parseFloat(cat.commission_value) || 0;
            if(cat.commission_type === 'flat') {
                return val;
            } else {
                return Math.round((this.formData.price * val) / 100);
            }
        },
        
        getCommissionText() {
            const cat = this.getCategory();
            if(!cat) return '';
            const val = parseFloat(cat.commission_value) || 0;
            if(cat.commission_type === 'flat') {
                return `(Flat ₹${val})`;
            } else {
                return `(${val}%)`;
            }
        },

        handleFiles(e) {
            const files = Array.from(e.target.files).slice(0, 5);
            this.previewImages = [];
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => { this.previewImages.push(e.target.result) };
                reader.readAsDataURL(file);
            });
        },
        
        removeImage(idx) {
            this.previewImages.splice(idx, 1);
        },
        
        fetchCategoryFields() {
            if(!this.formData.category_id) {
                this.dynamicFields = [];
                return;
            }
            this.loadingFields = true;
            fetch(`/products/category-fields?category_id=${this.formData.category_id}&condition_type=${this.formData.condition_type}`)
                .then(res => res.json())
                .then(data => {
                    this.dynamicFields = data.fields || [];
                    this.loadingFields = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loadingFields = false;
                });
        },

        validateStep() {
            if(this.step === 1) {
                if(!this.formData.name || !this.formData.category_id || this.previewImages.length === 0) {
                    alert('Please fill name, category and upload at least 1 image.');
                    return;
                }
            }
            if(this.step === 3) {
                if(!this.formData.price || this.formData.price <= 0) {
                    alert('Please enter a valid price.');
                    return;
                }
            }
            this.step++;
            window.scrollTo(0,0);
        }
    }))
});

document.getElementById('productForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> Publishing...';
    btn.classList.add('opacity-80', 'cursor-not-allowed');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideNav' => true, 'hideFooter' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/seller/products/create.blade.php ENDPATH**/ ?>